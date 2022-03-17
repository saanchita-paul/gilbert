<?php
namespace App\Modules\Reporting\Services;

use DB;
use App\Models\RejectionReason;
use App\Models\OfficeCommission;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ConnectionApplication;
use Illuminate\Database\Query\Builder;
use App\Services\Utility\GilbertStatusMapper;

class ExportWaterSubmissionReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private $timezone;
    private $serviceType;
    private $type;

    private array $waterType = [
        ConnectionService::TYPE_WATER,
    ];

    private array $acceptedWaterStates = ['Victoria', 'VIC'];
    private array $acceptedTenancyType = [ConnectionApplication::TENANCY_TYPE_RENTER];

    const STATUES_TO_KEEP = [
        ConnectionService::STATUS_EA_PROCESSINF, //Not submitted
        ConnectionService::STATUS_SUBMITTED, //In progress
        ConnectionService::STATUS_ENERGY_SUBMIT, //In progress
        ConnectionService::STATUS_ACCEPTED, //Accepted
        ConnectionService::STATUS_REJECTED, //Rejected
        ConnectionService::AC_MANUAL_PROCESSING, //MANUAL_PROCESSING
        ConnectionService::STATUS_CANT_CONNECT, //Failed
    ];

    public function __construct(string $type, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->type = $type;
        $this->serviceType = $this->waterType;
    }

    private array $leadsData = [];

    public function run()
    {
        $this->mapData($this->fetchData());
        return $this->export();
    }

    public function getAccessToke(){
        return 'token';
    }

    public function verifyAccessToken(){
        $this->run();
    }

    private function export()
    {
        $date = now()->format('d_m_Y');
        $name = 'Gilbert_leads_report_'.$this->type.'_'.$date.'.csv';
        return (new FastExcel($this->leadsData))->download($name);
    }

    private function mapData(array $data)
    {
        foreach ($data as $datum) {
            $datum->Lead_Source = $this->getLeadSrc($datum->Lead_Source);
            $datum->UI_Status = $this->getUiStatus($datum->Application_Status, $datum->UI_Status, $datum->Assigned_To);
            $datum->Application_Status = $this->getApplicationStatus($datum->Application_Status);
            $datum->Utility_Status = $this->getUtilityStatus($datum->Utility_Status);
            $datum->Street_Type = $this->getRoadType($datum->Street_Type);
            $datum->Tenancy_Type = $this->getTenancyType($datum->Tenancy_Type);
            $datum->Utility_Commission = $this->getUtilityCommission($datum->Office_Id, $datum->Utility_Service);
            $datum->Water_Only_Application = $this->isWaterOnlyApplication($datum->Water_Only_Application);

            $this->setAgencyName($datum);

            $datum->Rejection_Reason = $this->getRejectionReason($datum->Service_Id, $datum->Utility_Service);

            unset($datum->Office_Id);
            unset($datum->Foxie_Agency_Name);
            unset($datum->Foxie_Agent_Name);
            unset($datum->Assigned_To);

            if($this->allowedForExport($datum->Foxie_Connect_Id)) {
                unset($datum->Foxie_Connect_Id);
                $this->leadsData[] = $datum;
            }
        }
    }

    private function fetchData() : array
    {
        $waterType = $this->waterType;
        $builder = DB::table('connection_services as cs')
            ->selectRaw("
                ca.id as `App_id`,
                cs.id as `Service_Id`,
                ag.name as `Agency_Name`,
                IFNULL(ofs.name, 'NULL') as `Office_Name`,
                concat(ap.first_name, ap.last_name) as `Agent_Name`,
                IFNULL(u.email, 'NULL') as `Submitted_User_Email`,
                ca.source as `Lead_Source`,
                ca.first_name as `Customer_Firstname`,
                ca.last_name as `Customer_Lastname`,
                ca.office_id as `Office_Id`,
                ca.status as `Utility_Commission`,
                IFNULL(CONVERT_TZ(ca.created_at, '+00:00', '+10:00'), 'NULL') as `Lead_Created_Date`,
                CONVERT_TZ(ca.moving_date, '+00:00', '+10:00') as `Connection_Date`,
                IFNULL(CONVERT_TZ(cs.submitted_at, '+00:00', '+10:00'), 'NULL') as `Lead_Submitted_Date`,
                IFNULL(ca.unit_number, 'NULL') as `Unit_Number`,
                IFNULL(ca.street_number, 'NULL') as `Street_Number`,
                IFNULL(ca.street_name, 'NULL') as `Street_Name`,
                IFNULL(ca.street_name, 'NULL') as `Street_Type`,
                IFNULL(ca.city,'NULL') as `Suburb`,
                IFNULL(ca.state,'NULL') as `State`,
                IFNULL(ca.postcode,'NULL') as `Postcode`,
                IFNULL(ca.nmi,'NULL') as `NMI`,
                IFNULL(ca.mirn,'NULL') as `MIRN`,
                IFNULL(ca.assigned_to,'NULL') as `Assigned_To`,
                IFNULL(ca.tenancy_type,'NULL') as `Tenancy_Type`,
                cs.provider_name as `Utility_Provider`,
                cs.service_type as `Utility_Service`,
                IFNULL(sl.agency_name, 'NULL') as `Foxie_Agency_Name`,
                IFNULL(sl.agent_name, 'NULL') as `Foxie_Agent_Name`,
                IFNULL(sl.foxie_lead_source_description, 'NULL') as `Foxie_Lead_Description`,
                IFNULL(sl.compare_connect_id, 'NULL') as `Foxie_Connect_Id`,
                IFNULL(cs.distributor, 'NULL') as `Water_Distributor`,
                IFNULL(ca.fast_connect_customer_reference, 'NULL') as `Fast_Connect_Reference_Id`,
                IFNULL(ca.id, 'NULL') as `Water_Only_Application`,
                cs.status as `UI_Status`,
                ca.status as `Application_Status`,
                cs.status as `Utility_Status`
            ")
            ->rightJoin('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
            ->leftJoin('agencies as ag', 'ca.agency_id', '=', 'ag.id')
            ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
            ->leftJoin('offices as ofs', 'ofs.id', '=', 'ca.office_id')
            ->leftJoin('users as u', 'ca.submitted_by', '=', 'u.id')
            ->leftJoin('suger_leads as sl', 'ca.id', '=', 'sl.connection_application_id')
            ->where( function($q) use ($waterType) { $q->whereIn('cs.service_type', $waterType)->orWhereNull('cs.service_type'); } )
            ->where( function($q) { $q->whereIn('ca.state', $this->acceptedWaterStates)->orWhereNull('ca.state'); } )
            ->where( function($q) { $q->whereIn('ca.tenancy_type', $this->acceptedTenancyType)->orWhereNull('ca.tenancy_type'); } );

        $builder = $this->applyStatusFilter($builder);
        $tempBuilder = clone $builder;
        $filterWithCreatedDate = $this->filterWithCreatedDate($tempBuilder)->get()->toArray();
        $tempBuilder = clone $builder;
        $filterWithSubmittedDate = $this->filterWithSubmittedDate($tempBuilder)->get()->toArray();

        return array_merge(
            $filterWithCreatedDate,
            $filterWithSubmittedDate,
        );
    }

    private function isWaterOnlyApplication($applicationId) : string
    {
        $connectionApplication = ConnectionApplication::with('connectionServices')->find($applicationId);
        $serviceArray = $connectionApplication->connectionServices?->pluck('id')->toArray();
        return (count($serviceArray) === 1) ? 'Yes' : 'No';
    }

    private function applyStatusFilter(Builder $builder): Builder
    {
        return $builder->where(function (Builder $b) {
            $b->whereIn('cs.status', self::STATUES_TO_KEEP)->orWhereNull('cs.status');
        });
    }

    private function filterWithCreatedDate($builder)
    {
        return $builder
//            ->whereIn('cs.status', [
//                ConnectionService::STATUS_EA_PROCESSINF, //Not submitted
//                ConnectionService::STATUS_SUBMITTED, //In progress
//                ConnectionService::STATUS_ENERGY_SUBMIT, //In progress
//                ConnectionService::STATUS_ACCEPTED, //Accepted
//                ConnectionService::STATUS_REJECTED, //Rejected
//                ConnectionApplication::STATUS_CLOSED, //Closed
//                ConnectionService::AC_MANUAL_PROCESSING, //MANUAL_PROCESSING
//                ConnectionService::STATUS_CANT_CONNECT, //Failed
//            ])
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate);
    }

    private function filterWithSubmittedDate($builder)
    {
        return $builder
//            ->whereIn('cs.status', [
//                ConnectionService::STATUS_EA_PROCESSINF, //Not submitted
//                ConnectionService::STATUS_SUBMITTED, //In progress
//                ConnectionService::STATUS_ENERGY_SUBMIT, //In progress
//                ConnectionService::STATUS_ACCEPTED, //Accepted
//                ConnectionService::STATUS_REJECTED, //Rejected
//                ConnectionApplication::STATUS_CLOSED, //Closed
//                ConnectionService::AC_MANUAL_PROCESSING, //MANUAL_PROCESSING
//                ConnectionService::STATUS_CANT_CONNECT, //Failed
//            ])
            ->whereNotNull('cs.submitted_at')
            ->where('cs.submitted_at', '>=', $this->startDate)
            ->where('cs.submitted_at', '<=', $this->endDate)
            ->whereNotBetween('ca.created_at', [$this->startDate, $this->endDate]);
    }

    private function getLeadSrc(?int $src): string
    {
        $res = array_search($src, ConnectionApplication::SOURCE_MAPPING);
        return $res ?: "Unknown";
    }

    private function getTenancyType($type): string
    {
        if($type !== 'NULL') {
            return (int)$type === 1 ? 'renter' : 'homeowner';
        }
        return $type;
    }

    private function getRoadType($street_name)
    {
        $data = explode(' ', $street_name);
        return $data[sizeof($data) - 1];
    }

    private function getUiStatus($applicationStatus, $serviceStatus, $assignedTo)
    {
        if( (int) $applicationStatus === ConnectionApplication::STATUS_CLOSED) {
            return 'CLOSED';
        }
        elseif($serviceStatus === ConnectionService::STATUS_EA_PROCESSINF) {
            return $assignedTo === "NULL" ? 'UN_ASSIGNED' : 'ASSIGNED';
        }
        return GilbertStatusMapper::getStatusAsText($serviceStatus);
    }

    private function getApplicationStatus($applicationStatus)
    {
        return GilbertStatusMapper::getApplicationStatusAsText($applicationStatus);
    }

    private function getUtilityStatus($utilityStatus)
    {
        return GilbertStatusMapper::getUtilityStatusAsText($utilityStatus);
    }

    private function getRejectionReason($serviceId, $serviceType)
    {
        $reason = RejectionReason::where('connection_service_id', $serviceId)->first();
        return $reason  ?  $reason->reason_text : null;
    }
    
    private function getUtilityCommission($officeId, $serviceType)
    {
        if($officeId === null || $serviceType === null) {
            return 'NULL';
        }
        $serviceType = OfficeCommission::Type[$serviceType];

        $commission = OfficeCommission::where(['office_id' => $officeId, 'type' => $serviceType])->first();
        return $commission  ?  $commission->rate : 'NULL';
    }

    private function setAgencyName(object $datum)
    {
        if( $datum->Foxie_Agency_Name && $datum->Foxie_Agency_Name !== 'null') {
            $datum->Agency_Name = $datum->Foxie_Agency_Name;
        }

        if( $datum->Foxie_Agent_Name && $datum->Foxie_Agent_Name !== 'null') {
            $datum->Agent_Name = $datum->Foxie_Agent_Name;
        }
    }

    private function allowedForExport($foxieConnectID)
    {
        if($foxieConnectID !== null && $foxieConnectID !== 'NULL' && $foxieConnectID !== 'N/A') {
            return false;
        }
        return true;
    }
}
