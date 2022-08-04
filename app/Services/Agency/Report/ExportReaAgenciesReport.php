<?php


namespace App\Services\Agency\Report;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ExportReaAgenciesReport
{
    private Collection $mappedCSVData;

    public function __construct()
    {
        $this->fetchData();
    }

    public function run()
    {
        try {
            return (new FastExcel($this->mappedCSVData))->download($this->getCsvName());
        } catch (\Exception $exception) {
            \Log::error('ExportReaAgenciesReport:ERROR (see context for more information)', [
                'errorMessage' => $exception->getMessage(),
                'errorTrace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }
    }

    private function fetchData()
    {
        $query = DB::table('offices as o')
                ->join('agencies as a', 'o.agency_id', '=', 'a.id')
                ->select(
                    DB::raw(" 
                        o.name as 'Name',
                        IF(a.type=0, 'Independent', a.name) as Agency,
                        (select created_at from connection_applications where office_id = o.id order by created_at asc limit 1) as 'First Application Date',
                        (select created_at from connection_applications where office_id = o.id order by created_at desc limit 1) as 'Last Application Date',
                        (select DATEDIFF(NOW(), created_at) from connection_applications where office_id = o.id order by created_at desc limit 1) as 'Days Since Last Application'
                    ")
                )->get();

        $this->mappedCSVData = $query;

        return $this;
    }

    private function getCsvName()
    {
        $currentDate = Carbon::now()->format('Y_m_d_H_i');
        return 'Agencies_Report'.$currentDate.'.csv';
    }
}
