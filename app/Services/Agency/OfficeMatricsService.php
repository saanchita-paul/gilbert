<?php


namespace App\Services\Agency;


use App\Models\ConnectionService;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Utils;
use App\Models\ConnectionApplication;

class OfficeMatricsService
{
    public function __construct(public int $officeId)
    {

    }

    private function calculateApplicationMetrics($connectionServiceData)
    {

        $submittedStatuses = [ConnectionService::STATUS_SUBMITTED, ConnectionService::STATUS_ESCALATED, ConnectionService::STATUS_REJECTED, ConnectionService::STATUS_CLOSED];


        $connectionServiceData->power_submitted = $this->getDBBuilder()->whereHas('connectionServices', function($query) use ($submittedStatuses){
            $query->whereIn('status' , $submittedStatuses)->where('service_type', 'power');
        })->count();

        $connectionServiceData->power_connected = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_SUBMITTED )->whereHas('connectionServices', function($query){
            $query->where('status' , ConnectionService::STATUS_ACCEPTED)->where('service_type', 'power');
        })->count();

        $connectionServiceData->power_submitted =(int) $connectionServiceData->power_submitted - (int)$connectionServiceData->power_connected;

        $connectionServiceData->gas_submitted = $this->getDBBuilder()->whereHas('connectionServices', function($query) use ($submittedStatuses){
            $query->whereIn('status' , $submittedStatuses)->where('service_type', 'gas');
        })->count();

        $connectionServiceData->gas_connected = $this->getDBBuilder()->where('status', ConnectionApplication::STATUS_SUBMITTED)->whereHas('connectionServices', function($query){
            $query->where('status' , ConnectionService::STATUS_ACCEPTED)->where('service_type', 'gas');
        })->count();

        $connectionServiceData->gas_submitted =(int) $connectionServiceData->gas_submitted - (int)$connectionServiceData->gas_connected;


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

        return $builder->first();
    }


    private function calculateOfficeApplicationData()
    {
        $caCountedApp = ConnectionApplication::groupBy('status')
            ->select('status', DB::raw('count(*) as total'))
            ->where('office_id', $this->officeId)
            ->get();
        $caTotal = ConnectionApplication::query()->where('office_id', $this->officeId)->count();

        $totalConnectedApplication = DB::table('connection_applications', 'ca')
            ->leftJoin('connection_services' , 'connection_services.connection_application_id' ,
                '=' , 'ca.id')
            ->where('ca.office_id', $this->officeId)
            ->where('connection_services.status', ConnectionService::STATUS_ACCEPTED)
            ->where('ca.status', ConnectionService::STATUS_SUBMITTED)
            ->selectRaw('count(distinct connection_services.connection_application_id) as total')
            ->groupBy('ca.status','connection_services.status')
            ->first();




        $result = [
            'app_total' => $caTotal,
            'app_closed' => 0,
            'app_waiting_tenant' => 0,
            'app_submitted' => 0,
            'app_connected' => (int) $totalConnectedApplication?->total

        ];


        foreach($caCountedApp as $datum)
        {
            switch ($datum->status) {
                case ConnectionService::STATUS_CLOSED:
                    $result['app_closed'] = (int) $datum?->total;
                    break;
                case ConnectionService::STATUS_SUBMITTED:
                    $result['app_submitted'] = (int) $datum?->total - (int) $totalConnectedApplication?->total;
                    break;
                case ConnectionService::STATUS_UNASSIGNED:
                case ConnectionService::STATUS_ASSIGNED:
                    $result['app_waiting_tenant'] = $result['app_waiting_tenant'] + (int)$datum?->total;
                    break;

                default:
                    break;

            }
        }

        return $result;

    }

    public function get()
    {
        $connectionServiceData = $this->calculateServiceMetrics();
        $data = $this->calculateApplicationMetrics($connectionServiceData);
        $data->app_metrics = $this->calculateOfficeApplicationData();
        return $data;
    }

}
