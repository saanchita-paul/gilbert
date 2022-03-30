<?php
namespace App\Modules\Reporting\Services;

use DB;

use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Office;

class ExportReaOfficeReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;

    private array $agentReport = [];

    public function __construct(string $type, string $start, string $end)
    {
        $this->setDateRange($start, $end);
    }

    private array $leadsData = [];

    public function run()
    {
        $this->mapData($this->fetchData());
        return $this->export();
    }

    private function export()
    {
        $date = now()->format('d_m_Y');
        $name = 'OFFICE_REPORT_'.$date.'.csv';
        return (new FastExcel($this->leadsData))->download($name);
    }

    private function mapData(array $data)
    {
        foreach ($data as $datum) {

            $newData = [
                "agent_name" => $this->getAgentName($datum[0]['agent_id']),
                "applications_created" => count($datum),
                "applications_minimum_submitted" => 0,
                "successful_water" => 0,
                "successful_water" => 0,
                "awaiting_confirmation" => 0,
                "conversion_rate" => 0,
            ];
            
            $this->leadsData[] = $newData;
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

        Log::info($builder->get()->groupBy('agent_id')->toArray());

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
}
