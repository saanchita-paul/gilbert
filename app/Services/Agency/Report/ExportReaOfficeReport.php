<?php
namespace App\Services\Agency\Report;

use DB;

use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;

class ExportReaOfficeReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private string $officeId;
    private string $reportType;
    private array $officeReport = [];

    public function __construct(string $reportType, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        // $this->officeId = $officeId;
        // $this->reportType = $reportType;
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

            $this->officeReport = [
                "detailedCount" => $detailedCount,
                "totalCount" => $totalCount,
                "submittedUtilityCount" => $submittedUtilityCount
            ];
        }
    }

    private function fetchData() : array
    {
        // $builder = DB::table('connection_services as cs')
        //     ->selectRaw("
        //         ca.office_id as `office_id`,
        //         ca.created_by as `agent_id`,
        //         concat(ap.first_name, ap.last_name) as `agent_name`,
        //         ca.id as `application_id`,
        //         ca.status as `application_status`,
        //         cs.id as `utility_id`,
        //         cs.status as `utility_status`,
        //         cs.service_type as `utility_type`,
        //         ca.created_at as `created_date`,
        //         cs.submitted_at as `submitted_date`
        //     ")
        //     ->leftJoin('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
        //     ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
        //     ->where('ca.created_at', '>=', $this->startDate)
        //     ->where('ca.created_at', '<=', $this->endDate)
        //     ->where('ca.created_by', '!=', null);
          

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
                ->where('created_by', '!=', null);
                // ->where('office_id', $this->officeId);

        return $builder->get()->groupBy('agent_id')->toArray();
    }

    private function getAgentName(int $id) : string
    {
        $agentProfile = DB::table('agent_profiles')
            ->select('first_name', 'last_name')
            ->where('id', $id)
            ->first();
        return $agentProfile->first_name.' '.$agentProfile->last_name;
    }

    private function getMinimumSubmitted(array $applications) : int
    {
        $submitted = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if ($service['utility_type'] !== 'water' && (
                    $service['utility_status'] === 4 ||
                    $service['utility_status'] === 5 ||
                    $service['utility_status'] === 6
                    )
                ) {
                    $submitted++;
                    break;
                }
            }
        }
        return $submitted;
    }

    private function getSuccessfulWaterConnection(array $applications) : int
    {
        $submitted = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if ($service['utility_type'] === 'water' && (
                    $service['utility_status'] === 4 ||
                    $service['utility_status'] === 5 ||
                    $service['utility_status'] === 6
                    )
                ) {
                    $submitted++;
                    break;
                }
            }
        }
        return $submitted;
    }

    private function getAwaitingConfirmation(array $applications) : int
    {
        $awaiting = 0;
        foreach ($applications as $application) {
            if($application['application_status'] === 1 || $application['application_status'] === 2) {
                $awaiting++;
            }
        }
        return $awaiting;
    }

    private function getConversionRate(int $total, int $minimum, int $awaiting) : float
    {
        $conversionRate = 0;
        if ($total > 0) {
            $conversionRate = (($minimum / $total) - $awaiting);
        }
        return $conversionRate;
    }
}
