<?php
namespace App\Services\Agency\Report;

use App\Models\AgentProfile;
use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use PDF;

class ExportReaOfficeReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private string $officeId;
    private string $reportType;
    private array $officeReport = [];

    private array $submissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT
    ];

    private array $energyType = [
        ConnectionService::TYPE_ELECTRICITY,
        ConnectionService::TYPE_GAS
    ];

    private array $WaterType = [ConnectionService::TYPE_WATER];

    private array $awaitingConfirmationType = [
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    public function __construct(string $officeId, string $reportType, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->officeId = $officeId;
        $this->reportType = $reportType;
    }

    public function run()
    {
        $this->mapData($this->fetchData());
        return $this->export();
    }

    private function export()
    {
        // create PDF here
        Log::info('Exporting report', $this->officeReport);
        $data = ['image' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png'];
        $pdf = PDF::loadView('pdf.invoice_office', $data);
        return $pdf->inline();

    }

    private function mapData(array $data)
    {
        $detailedCount = [];
        $totalCount = [
            'total_applications_created' => 0,
            'applications_with_minimum_submitted' => 0,
            'successful_water_connections' => 0,
            'awaiting_confirmation' => 0,
            'conversion_rate' => 0
        ];

        $submittedUtilityCount = [
            'electricity' => 0,
            'gas' => 0,
            'water' => 0,
            'total_energy' => 0,
        ];

        foreach ($data as $datum) {

            $count = [
                "agent_name" => $this->getAgentName($datum[0]['agent_id']),
                "total_applications_created" => count($datum),
                "applications_with_minimum_submitted" => $this->getMinimumSubmitted($datum),
                "successful_water_connections" => $this->getSuccessfulWaterConnection($datum),
                "awaiting_confirmation" => $this->getAwaitingConfirmation($datum),
            ];
            $count['conversion_rate'] = $this->getConversionRate($count['total_applications_created'], $count['applications_with_minimum_submitted'], $count['awaiting_confirmation']);
            
            $detailedCount[] = $count;

            $totalCount['total_applications_created'] += $count['total_applications_created'];
            $totalCount['applications_with_minimum_submitted'] += $count['applications_with_minimum_submitted'];
            $totalCount['successful_water_connections'] += $count['successful_water_connections'];
            $totalCount['awaiting_confirmation'] += $count['awaiting_confirmation'];
            $totalCount['conversion_rate'] = $this->getConversionRate($totalCount['total_applications_created'], $totalCount['applications_with_minimum_submitted'], $totalCount['awaiting_confirmation']);

            $submittedUtilityCount['electricity'] += $this->getSubmittedUtilityCount($datum, 'power');
            $submittedUtilityCount['gas'] += $this->getSubmittedUtilityCount($datum, 'gas');
            $submittedUtilityCount['water'] += $this->getSubmittedUtilityCount($datum, 'water');
        }
        $submittedUtilityCount['total_energy'] = $submittedUtilityCount['electricity'] + $submittedUtilityCount['gas'];

        $this->officeReport = [
            "detailedCount" => $detailedCount,
            "totalCount" => $totalCount,
            "submittedUtilityCount" => $submittedUtilityCount
        ];
    }

    private function fetchData() : array
    {
        $builder = ConnectionApplication::selectRaw("
            office_id as `office_id`,
            created_by as `agent_id`,
            id,
            status as `application_status`,
            created_at as `created_date`")
                ->with(['connectionServices' => function ($query) {
                    $query->selectRaw("
                            id as `utility_id`,
                            connection_application_id,
                            status as `utility_status`,
                            service_type as `utility_type`,
                            submitted_at as `submitted_date`
                        ");
                }])
                ->where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->where('created_by', '!=', null)
                ->where('office_id', $this->officeId);

        return $builder->get()->groupBy('agent_id')->toArray();
    }

    private function getAgentName(int $id) : string
    {
        $agentProfile = AgentProfile::selectRaw("id, first_name, last_name")->where('id', $id)->first();
        return $agentProfile->first_name.' '.$agentProfile->last_name;
    }

    private function getMinimumSubmitted(array $applications) : int
    {
        $energySubmitted = 0;

        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    in_array($service['utility_type'], $this->energyType) &&
                    in_array($service['utility_status'], $this->submissionType)
                ) {
                    $energySubmitted++;
                    break;
                }
            }
        }
        return $energySubmitted;
    }

    private function getSuccessfulWaterConnection(array $applications) : int
    {
        $waterSubmitted = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    in_array($service['utility_type'], $this->WaterType) &&
                    in_array($service['utility_status'], $this->submissionType)
                ) {
                    $waterSubmitted++;
                    break;
                }
            }
        }
        return $waterSubmitted;
    }

    private function getAwaitingConfirmation(array $applications) : int
    {
        $awaiting = 0;
        foreach ($applications as $application) {
            if(in_array($application['application_status'], $this->awaitingConfirmationType )) {
                $awaiting++;
            }
        }
        return $awaiting;
    }

    private function getConversionRate(int $total, int $minimum, int $awaiting) : float
    {
        $conversionRate = 0;
        if ($total > 0 && ($total - $awaiting > 0)) {
            $conversionRate = ($minimum / ($total - $awaiting)) * 100;
        }
        return $conversionRate;
    }

    private function getSubmittedUtilityCount(array $applications, string $utilityType) : int
    {
        $count = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    $service['utility_type'] === $utilityType &&
                    in_array($service['utility_status'], $this->submissionType)
                ) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }
}
