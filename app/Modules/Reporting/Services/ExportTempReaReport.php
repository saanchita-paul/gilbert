<?php
namespace App\Modules\Reporting\Services;

use DB;

use App\Models\ConnectionService;
use Rap2hpoutre\FastExcel\FastExcel;


class ExportTempReaReport
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
        $name = 'Rea_pdf_'.$date.'.csv';
        return (new FastExcel($this->leadsData))->download($name);
    }

    private function mapData(array $data)
    {
        foreach ($data as $datum) {

            //push to $agentReport array
            
            

            $this->leadsData[] = $datum;
        }
    }

    private function fetchData() : array
    {
        $builder = DB::table('connection_services as cs')
            ->selectRaw("
                ca.office_id as `office_id`,
                ca.created_by as `agent_id`,
                concat(ap.first_name, ap.last_name) as `agent_name`,
                ca.id as `application_id`,
                ca.status as `application_status`,
                cs.id as `utility_id`,
                cs.status as `utility_status`,
                cs.service_type as `utility_type`,
                ca.created_at as `created_date`,
                cs.submitted_at as `submitted_date`
            ")
            ->leftJoin('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
            ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate)
            ->where('ca.created_by', '!=', null);
            // where office id matches with current office id

        return $builder->get()->toArray();
    }
}
