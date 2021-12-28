<?php


namespace App\Services\Agency;


use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Utils;

class OfficeMatricsService
{


    public function __construct(public int $officeId)
    {

    }



    private function calculateServiceMetrics()
    {
        $builder = DB::table('connection_applications', 'ca');
        $builder->where('office_id', $this->officeId);
        $builder->leftJoin('connection_services as cs', 'ca.id', '=', 'cs.connection_application_id')
            ->select(
                DB::raw("IFNULL(SUM(CASE
            WHEN cs.status = 4 THEN 1 ELSE 0 END), 0) AS app_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.status = 5 THEN 1 ELSE 0 END), 0) AS app_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'power' AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS power_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'power' AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS power_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'gas' AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS gas_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'gas' AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS gas_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'internet' AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS internet_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'internet' AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS internet_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN ca.status = 8 THEN 1 ELSE 0 END), 0) AS app_closed"),

                DB::raw("IFNULL(SUM(CASE
            WHEN ca.status = 1 THEN 1 ELSE 0 END), 0) AS app_unassigned"),
                DB::raw("COUNT(*) AS app_total"),
            );


        return $builder->get();

    }



    public function get()
    {
        return $connectionServiceData = $this->calculateServiceMetrics();
    }

}
