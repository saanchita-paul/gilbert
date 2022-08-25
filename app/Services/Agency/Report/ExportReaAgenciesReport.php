<?php


namespace App\Services\Agency\Report;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Services\TimeZoneService;

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
            $this->handleException($exception);
        }
    }

    private function fetchData()
    {
        try {
            $tz = '+' . TimeZoneService::getTimeZoneInt() . ':00';
            $query = DB::table('offices as o')
            ->join('agencies as a', 'o.agency_id', '=', 'a.id')
            ->select(
                DB::raw(" 
                    o.name as 'Office Name',
                    IF(a.type=0, 'Independent', a.name) as Agency,
                    DATE_FORMAT(CONVERT_TZ((select created_at from connection_applications where office_id = o.id order by created_at asc limit 1), '+00:00', '$tz'), '%d/%m/%Y') as 'First Application Date',
                    DATE_FORMAT(CONVERT_TZ((select created_at from connection_applications where office_id = o.id order by created_at desc limit 1), '+00:00', '$tz'), '%d/%m/%Y') as 'Last Application Date',
                    IF((select count(*) from connection_applications where office_id = o.id > 0), IFNULL((select DATEDIFF(CONVERT_TZ(UTC_TIMESTAMP(), '+00:00', '$tz'), CONVERT_TZ(created_at, '+00:00', '+10:00')) from connection_applications where office_id = o.id order by created_at desc limit 1), 0), null) as 'Days Since Last Application'
                ")
            )->get();

            $this->mappedCSVData = $query;
            return $this;
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    private function getCsvName()
    {
        $currentDate = Carbon::now()->format('Y_m_d_H_i');
        return 'Agencies_Report_'.$currentDate.'.csv';
    }

    private function handleException(\Exception $e){
        \Log::error('Export REA Agencies Report FAIL (see context for more information)', [
            'errorMessage' => $e->getMessage(),
            'errorTrace' => $e->getTraceAsString(),
        ]);
        
        throw $e;
    }
}
