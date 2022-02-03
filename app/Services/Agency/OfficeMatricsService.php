<?php


namespace App\Services\Agency;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\DB;

class OfficeMatricsService
{
    public function __construct(public int $officeId)
    {

    }

    private function calculateApplicationMatrics($connectionServiceData)
    {

        $submittedStatuses = [ConnectionService::STATUS_SUBMITTED, ConnectionService::STATUS_ESCALATED, ConnectionService::STATUS_REJECTED, ConnectionService::STATUS_CLOSED];

        $connectionServiceData[0]->app_total = $this->getDBBuilder()->count();
        $connectionServiceData[0]->app_unassigned = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_UNASSIGNED)->count();
        $connectionServiceData[0]->app_assigned = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_ASSIGNED)->count();


        $connectionServiceData[0]->app_submitted = $this->getDBBuilder()->whereHas('connectionServices', function($query) use ($submittedStatuses) {
            $query->whereIn('status' , $submittedStatuses)->whereIn('service_type', ['power', 'gas']);
        })->count();

        $connectionServiceData[0]->app_connected = $this->getDBBuilder()->whereHas('connectionServices', function($query){
            $query->whereIn('status' , [ConnectionService::STATUS_ACCEPTED])->whereIn('service_type', ['power', 'gas']);
        })->count();


        $connectionServiceData[0]->power_submitted = $this->getDBBuilder()->whereHas('connectionServices', function($query) use ($submittedStatuses){
            $query->whereIn('status' , $submittedStatuses)->where('service_type', 'power');
        })->count();

        $connectionServiceData[0]->power_connected = $this->getDBBuilder()->whereHas('connectionServices', function($query){
            $query->where('status' , ConnectionService::STATUS_ACCEPTED)->where('service_type', 'power');
        })->count();

        $connectionServiceData[0]->gas_submitted = $this->getDBBuilder()->whereHas('connectionServices', function($query) use ($submittedStatuses){
            $query->whereIn('status' , $submittedStatuses)->where('service_type', 'gas');
        })->count();

        $connectionServiceData[0]->gas_connected = $this->getDBBuilder()->whereHas('connectionServices', function($query){
            $query->where('status' , ConnectionService::STATUS_ACCEPTED)->where('service_type', 'gas');
        })->count();


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
            WHEN cs.service_type = 'internet' AND ca.status NOT IN (3, 8) AND cs.status = 4 THEN 1 ELSE 0 END), 0) AS internet_submitted"),

                DB::raw("IFNULL(SUM(CASE
            WHEN cs.service_type = 'internet' AND ca.status NOT IN (3, 8) AND cs.status = 5 THEN 1 ELSE 0 END), 0) AS internet_connected"),

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
