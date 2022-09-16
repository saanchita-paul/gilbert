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

    const STATES  = [
        'VIC',
        'NSW',
        'QLD',
        'ATC',
        'SA',
        'WA'
    ];

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
            $regex = $this->getStatesRegex();
//            dd($regex);

            $query = DB::table('offices as o')
                ->join('agencies as a', 'o.agency_id', '=', 'a.id')
                ->select(
                    DB::raw("
                    o.name as 'Office Name',
                    UPPER(REGEXP_SUBSTR(o.address, '$regex')) as State,
                    DATE_FORMAT(CONVERT_TZ((select created_at from connection_applications where office_id = o.id order by created_at asc limit 1), '+00:00', '$tz'), '%d/%m/%Y') as 'First Application Date',
                    DATE_FORMAT(CONVERT_TZ((select created_at from connection_applications where office_id = o.id order by created_at desc limit 1), '+00:00', '$tz'), '%d/%m/%Y') as 'Last Application Date',
                    IF((select count(*) from connection_applications where office_id = o.id > 0), IFNULL((select DATEDIFF(CONVERT_TZ(UTC_TIMESTAMP(), '+00:00', '$tz'), CONVERT_TZ(created_at, '+00:00', '$tz')) from connection_applications where office_id = o.id order by created_at desc limit 1), 0), null) as 'Days Since Last Application',
                    (select count(*) from connection_applications where office_id = o.id) as `Total Applications`,
                    IFNULL(o.rent_roll, 0) as PUM
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

    /**
     * Building regex for states
     *
     * @return string
     */
    private function getStatesRegex(): string
    {
        $r = '';
        foreach (self::STATES as $state) {
            $r .= !empty($r) ? '|' : '';

            $r .= '\\\\b' . $state . '\\\\b';
        }
        return $r;
    }
}
