<?php
namespace App\Modules\Reporting\Services;

use App\Models\ConnectionApplication;
use App\Services\Utility\GilbertStatusMapper;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ConnectionService;
use App\Models\RejectionReason;

class ExportSubmissionReport
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

    private function export()
    {
        // $name = now()->format('U') . '.csv';
        $date = now()->format('d_m_Y');
        $name = 'Gilbert_leads_report_'.$this->type.'_'.$date.'.csv';
        return (new FastExcel($this->leadsData))->download($name);
    }

    private function mapData(array $data)
    {
        //show assigned unassigned// show closed status
        foreach ($data as $datum) {
            $datum->Lead_Source = $this->getLeadSrc($datum->Lead_Source);
            
            info('id' , ['service id ' , $datum->Service_ID]);

            $datum->Lead_Status = $this->getStatus($datum->Application_Status, $datum->Lead_Status, $datum->Assigned_To);
            $datum->Street_Type = $this->getRoadType($datum->Street_Type);
            $datum->Customer_Type = $this->getCustomerType($datum->Customer_Type);
            $datum->Offer_Type = 'ENE';
            // $datum->Lead_Submitted_Date = $datum->Lead_Submitted_Date ?? 'NULL';
            // $datum->Unit_Number = $datum->Unit_Number ?? 'NULL';
            // $datum->Vendor_ID = $datum->Vendor_ID ?? 'NULL';
            $datum->Source_Code = $this->getSourceCode($datum->Utility_Service, $datum->State, $datum->Utility_Plan, $datum->Postcode);
            
            $this->setAgencyName($datum);

            $datum->Rejection_Reason = $this->getRejectionReason($datum->Service_Id, $datum->Utility_Service);

            unset($datum->Service_Id);
            unset($datum->Foxie_Agency_Name);
            unset($datum->Foxie_Agent_Name);
            unset($datum->Assigned_To);
            unset($datum->Application_Status);

            if($this->allowedForExport($datum->Utility_Provider, $datum->Lead_Status)) {
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
                ag.name as `Agency_Name`,
                cs.id as `Service_ID`,
                concat(ap.first_name, ap.last_name) as `Agent_Name`,
                IFNULL(u.email, 'NULL') as `Submitted_User_Email`,
                ca.source as `Lead_Source`,
                ca.first_name as `Customer_Firstname`,
                ca.last_name as `Customer_Lastname`,
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
                IFNULL(ca.status,'NULL') as `Source_Code`,
                IFNULL(ca.assigned_to,'NULL') as `Assigned_To`,
                IFNULL(ca.status,'NULL') as `Application_Status`,
                cs.lead_reference as `Lead_Reference`,
                cs.provider_name as `Utility_Provider`,
                cs.service_type as `Utility_Service`,
                cs.plan_type as `Utility_Plan`,
                cs.quote_reference as `Quote_ID`,
                cs.status as `Lead_Status`,
                sl.agency_name as `Foxie_Agency_Name`,
                sl.agent_name as `Foxie_Agent_Name`,
                cs.id as `Service_Id`
            ")
            ->rightJoin('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
            ->leftJoin('agencies as ag', 'ca.agency_id', '=', 'ag.id')
            ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
            ->leftJoin('users as u', 'ca.submitted_by', '=', 'u.id')
            ->leftJoin('suger_leads as sl', 'ca.id', '=', 'sl.connection_application_id')
            ->where( function($q) use ($energyType) { $q->whereIn('cs.service_type', $energyType)->orWhereNull('cs.service_type'); } );
            // ->whereNotNull('cs.provider_name');

        $tempBuilder = clone $builder;
        $filterWithCreatedDate = $this->filterWithCreatedDate($tempBuilder)->get()->toArray();

        $tempBuilder = clone $builder;
        $filterWithSubmittedDate = $this->filterWithSubmittedDate($tempBuilder)->get()->toArray();

        return array_merge(
            $filterWithCreatedDate,
            $filterWithSubmittedDate,
        );
    }

    private function filterWithCreatedDate($builder)
    {
        return $builder
            ->whereIn('cs.status', [
                ConnectionService::STATUS_EA_PROCESSINF, //Not submitted
                ConnectionService::STATUS_SUBMITTED, //In progress
                ConnectionService::STATUS_ENERGY_SUBMIT, //In progress
                ConnectionService::STATUS_ACCEPTED, //Accepted
                ConnectionService::STATUS_REJECTED, //Rejected
                ConnectionApplication::STATUS_CLOSED, //Closed
                ConnectionService::AC_MANUAL_PROCESSING, //MANUAL_PROCESSING
                ConnectionService::STATUS_CANT_CONNECT, //Failed
            ])
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate);
    }

    private function filterWithSubmittedDate($builder)
    {
        return $builder
            ->whereIn('cs.status', [
                ConnectionService::STATUS_EA_PROCESSINF, //Not submitted
                ConnectionService::STATUS_SUBMITTED, //In progress
                ConnectionService::STATUS_ENERGY_SUBMIT, //In progress
                ConnectionService::STATUS_ACCEPTED, //Accepted
                ConnectionService::STATUS_REJECTED, //Rejected
                ConnectionApplication::STATUS_CLOSED, //Closed
                ConnectionService::AC_MANUAL_PROCESSING, //MANUAL_PROCESSING
                ConnectionService::STATUS_CANT_CONNECT, //Failed
            ])
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

    private function getRoadType($street_name)
    {
        $data = explode(' ', $street_name);
        return $data[sizeof($data) - 1];
    }

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

    private function getStatus($leadStatus, $serviceStatus, $assignedTo)
    {
        if($leadStatus === ConnectionApplication::STATUS_CLOSED) {
            return 'CLOSED';
        }
        elseif($serviceStatus === ConnectionService::STATUS_EA_PROCESSINF) {
            return $assignedTo === null ? 'UN_ASSIGNED' : 'ASSIGNED';
        }
        return GilbertStatusMapper::getStatusAsText($serviceStatus);
    }

    private function getRejectionReason($serviceId, $serviceType)
    {
        $reason = RejectionReason::where('connection_service_id', $serviceId)->first();
        return $reason  ?  $reason->reason_text : null;
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
        if( $datum->Foxie_Agency_Name && $datum->Foxie_Agency_Name !== 'null') {
            $datum->Agency_Name = $datum->Foxie_Agency_Name;
        }

        if( $datum->Foxie_Agent_Name && $datum->Foxie_Agent_Name !== 'null') {
            $datum->Agent_Name = $datum->Foxie_Agent_Name;
        }
    }

    private function allowedForExport($serviceProvider, $serviceStatus)
    {
        $nonEmptyProviders = ['IN_PROGRESS', 'MANUAL_PROCESSING', 'ACCEPTED', 'REJECTED'];

        if(in_array($serviceStatus, $nonEmptyProviders) && $serviceProvider === null) {
            return false;
        }
        return true;
    }
}
