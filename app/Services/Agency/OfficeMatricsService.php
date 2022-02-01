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

    private function calculateApplicationMatrics($connectionServiceData)
    {
        $connectionServiceData[0]->app_total = $this->getDBBuilder()->count();
        $connectionServiceData[0]->app_unassigned = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_UNASSIGNED)->count();
        $connectionServiceData[0]->app_assigned = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_ASSIGNED)->count();
        
        
        $connectionServiceData[0]->app_submitted = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_SUBMITTED)->count();
        $connectionServiceData[0]->app_connected = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_ACCEPTED)->count();
        

        $connectionServiceData[0]->app_closed = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_CLOSED)->count();

        return $connectionServiceData;
    }

    private function getDBBuilder(){
        return ConnectionApplication::where('office_id', $this->officeId);
    }

    private function calculateServiceMetrics()
    {
        $builder = DB::table('connection_applications', 'ca');
        $builder->where('office_id', $this->officeId);
        $builder->leftJoin('connection_services as cs', 'ca.id', '=', 'cs.connection_application_id')
            ->select(
                DB::raw("IFNULL(SUM(CASE
            WHEN cs.status = 4 AND ca.status NOT IN (3, 8) THEN 1 ELSE 0 END), 0) AS app_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.status = 5 AND ca.status NOT IN (3, 8) THEN 1 ELSE 0 END), 0) AS app_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'power' AND ca.status NOT IN (3, 8) AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS power_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'power' AND ca.status NOT IN (3, 8) AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS power_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'gas' AND ca.status NOT IN (3, 8) AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS gas_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'gas' AND ca.status NOT IN (3, 8) AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS gas_connected"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'internet' AND ca.status NOT IN (3, 8) AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS internet_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'internet' AND ca.status NOT IN (3, 8) AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS internet_connected"),


                DB::raw("IFNULL(SUM(CASE
            WHEN ca.status = 1 THEN 1 ELSE 0 END), 0) AS app_unassigned"),
                DB::raw("COUNT(*) AS app_total"),
            );

        return $builder->get();
    }

    public function get()
    {   
        
        $connectionServiceData = $this->calculateServiceMetrics();
        $connectionApplicationData = $this->calculateApplicationMatrics($connectionServiceData);
        return $connectionApplicationData;
    }

}
