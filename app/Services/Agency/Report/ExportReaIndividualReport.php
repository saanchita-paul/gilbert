<?php
namespace App\Services\Agency\Report;

use App\Models\AgentProfile;
use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use PDF;
use Carbon\Carbon;

class ExportReaIndividualReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private string $stringStartDate;
    private string $stringEndDate;
    private string $officeId;
    private string $agentId;
    private string $reportType;
    private array $individualReport = [];

    private array $submissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_CANT_CONNECT
    ];

    private array $nonRejectedSubmissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
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

    public function __construct(string $officeId, string $agentId, string $reportType, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->officeId = $officeId;
        $this->agentId = $agentId;
        $this->reportType = $reportType;
        $this->stringStartDate = Carbon::parse($start)->format('M d Y');
        $this->stringEndDate = Carbon::parse($end)->format('M d Y');

    }

    public function run()
    {
        $this->mapData($this->fetchData());
        // return $this->export();
    }

    private function export()
    {
        Log::info('REA Individual report', $this->individualReport);
        
        $agent = AgentProfile::find($this->agentId);
        $agentName = $agent->first_name . ' ' . $agent->last_name;

        $data = [
            'agentName' => $agentName,
            'startDate' => $this->stringStartDate,
            'endDate' => $this->stringEndDate,
            'report' => $this->individualReport
        ];
        $pdf = PDF::loadView('pdf.report_individual', $data);
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
                "app_id" => 1,
                "lead_source" => 1,
                "customer_name" => 'name',
                "created_date" => '2/6/2022',
                "connection_date" => '2/9/2022',
                "full_address" => 'address here',
                "rejection_reason" => 'reason here',
                "customer_type" => 'type here',
                "is_electricity_submitted" => 1,
                "electricity_status" => 'submitted',
                "is_gas_submitted" => 0,
                "gas_status" => 'assigned',
                "is_water_submitted" => 1,
                "water_status" => 'rejected'
            ];
            
            $detailedCount[] = $count;


            $totalCount['total_applications_created'] = 12;
            $totalCount['applications_with_minimum_submitted'] = 13;
            $totalCount['successful_water_connections'] = 14;
            $totalCount['awaiting_confirmation'] = 15;
            $totalCount['conversion_rate'] = 16;

            $submittedUtilityCount['electricity'] = 6;
            $submittedUtilityCount['gas'] = 7;
            $submittedUtilityCount['water'] = 8;
        }
        $submittedUtilityCount['total_energy'] = 12;

        $this->individualReport = [
            "detailedCount" => $detailedCount,
            "totalCount" => $totalCount,
            "submittedUtilityCount" => $submittedUtilityCount
        ];

        Log::info('REA Individual report', $this->individualReport);
    }

    private function fetchData() : array
    {
        $builder = ConnectionApplication::selectRaw("
            office_id as `office_id`,
            created_by as `agent_id`,
            id,
            source as `lead_source`,
            first_name as `customer_first_name`,
            last_name as `customer_last_name`,
            status as `application_status`,
            CONVERT_TZ(created_at, '+00:00', '+10:00') as `created_date`,
            CONVERT_TZ(moving_date, '+00:00', '+10:00') as `connection_date`,
            address_text as `full_address`,
            property_type as `customer_type`
            ")
                ->with(['connectionServices' => function ($query) {
                    $query->selectRaw("
                        id,
                        connection_application_id,
                        status as `utility_status`,
                        service_type as `utility_type`
                    ")
                    ->with(['reasons' => function ($query) {
                        $query->selectRaw("
                            id as `rejection_id`,
                            connection_service_id,
                            reason_text as `rejection_reason`,
                            created_at as `created_date`
                        ");
                    }]);
                }])
                ->where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->where('created_by', '!=', null)
                ->where('office_id', $this->officeId);

        return $builder->get()->toArray();
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
        return round($conversionRate, 1);
    }

    private function getSubmittedUtilityCount(array $applications, string $utilityType) : int
    {
        $count = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    $service['utility_type'] === $utilityType &&
                    in_array($service['utility_status'], $this->nonRejectedSubmissionType)
                ) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }
}
