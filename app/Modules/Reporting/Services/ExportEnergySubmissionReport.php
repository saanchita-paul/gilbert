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

class ExportEnergySubmissionReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private $timezone;
    private $serviceType;
    private $type;
    private $chatbotUri;

    private array $energyType = [
        ConnectionService::TYPE_ELECTRICITY,
        ConnectionService::TYPE_GAS
    ];

    private array $waterType = [
        ConnectionService::TYPE_WATER,
    ];

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
        $this->serviceType = $type === 'energy' ? $this->energyType : $this->waterType;
        $this->chatbotUri = config('bot.root_url');
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
            $datum->Customer_Type = $this->getCustomerType($datum->Customer_Type);
            $datum->Offer_Type = 'ENE';
            $datum->Tenancy_Type = $this->getTenancyType($datum->Tenancy_Type);
            $datum->Utility_Commission = $this->getUtilityCommission($datum->Office_Id, $datum->Utility_Service);
            // $datum->Source_Code = $this->getSourceCode($datum->Utility_Service, $datum->State, $datum->Utility_Plan, $datum->Postcode);

            $this->setAgencyName($datum);

            $datum->Rejection_Reason = $this->getRejectionReason($datum->Service_Id, $datum->Utility_Service);

            unset($datum->Office_Id);
            unset($datum->Foxie_Agency_Name);
            unset($datum->Foxie_Agent_Name);
            unset($datum->Assigned_To);
            // unset($datum->Application_Status);

            if($this->allowedForExport($datum->Foxie_Connect_Id)) {
                unset($datum->Foxie_Connect_Id);
                $this->leadsData[] = $datum;
            }
        }
    }

    // Offer_Type, Source_Code is faked assigned just to place the data in the right order
    private function fetchData() : array
    {
        $energyType = $this->energyType;
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
                IFNULL(ca.vendor_id,'NULL') as `Vendor_ID`,
                IFNULL(ca.nmi,'NULL') as `NMI`,
                IFNULL(ca.mirn,'NULL') as `MIRN`,
                IFNULL(ca.property_type,'NULL') as `Customer_Type`,
                IFNULL(ca.status,'NULL') as `Offer_Type`,
                IFNULL(ca.assigned_to,'NULL') as `Assigned_To`,
                IFNULL(ca.tenancy_type,'NULL') as `Tenancy_Type`,
                cs.lead_reference as `Lead_Reference`,
                cs.provider_name as `Utility_Provider`,
                cs.service_type as `Utility_Service`,
                cs.plan_type as `Utility_Plan`,
                cs.quote_reference as `Quote_ID`,
                IFNULL(sl.agency_name, 'NULL') as `Foxie_Agency_Name`,
                IFNULL(sl.agent_name, 'NULL') as `Foxie_Agent_Name`,
                IFNULL(sl.foxie_lead_source_description, 'NULL') as `Foxie_Lead_Description`,
                IFNULL(sl.compare_connect_id, 'NULL') as `Foxie_Connect_Id`,
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
            ->where( function($q) use ($energyType) { $q->whereIn('cs.service_type', $energyType)->orWhereNull('cs.service_type'); } );
            // ->whereNotNull('cs.provider_name');
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

    // Not using this function anymore, can delete
    private function getSourceCode($service, $state, $plan, $postcode)
    {
        $state = $this->stateMap($state);

        try {
            $source_url = $service === 'power' ? 'ele-source-code' : 'gas-source-code';
            $response = Http::withoutVerifying()->post("$this->chatbotUri/api/$source_url", ['plan' => $plan, 'state' => $state, 'postcode' => $postcode]);
            if ($response->status() == 200) {
                return json_decode($response->body())->source_code;
            }
        } catch (\Exception $e) {
            Log::error("[ExportEnergyReport:getElectricitySourceCode] ->  " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return  '';
        }

        return  '';
    }

    private function stateMap($state)
    {
        $stateList = [
            'New South Wales' => 'NSW', 'Victoria' => 'VIC', 'Queensland' => 'QLD',
            'South Australia' => 'SA', 'Northern Territory' => 'NT', 'TAS' => 'Tasmania', 'ACT' => 'Australian Capital Territory', 'WA' => 'Western Australia'
        ];
        if (array_key_exists($state, $stateList)) {
            return $stateList[$state];
        }
        return $state;
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
        \Log::info("serviceId ---->",[$serviceId]);
        $application = ConnectionApplication::where('id', $serviceId)->get();
        $reason = RejectionReason::where('connection_service_id', $serviceId)->first();
        \Log::info("application ---->",[$application->app_close_reason_id]);
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

    private function getCustomerType($customerType)
    {
        return match ($customerType) {
            1 => 'RESI',
            2 => 'SME',
            default => null
        };
    }

    private function setAgencyName(object $datum)
    {
        if( $datum->Foxie_Agency_Name && $datum->Foxie_Agency_Name !== 'NULL') {
            $datum->Agency_Name = $datum->Foxie_Agency_Name;
        }

        if( $datum->Foxie_Agent_Name && $datum->Foxie_Agent_Name !== 'NULL') {
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
