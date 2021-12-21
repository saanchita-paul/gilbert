<?php
namespace App\Modules\Reporting\Services;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\HoodProfile;
use App\Services\Utility\GilbertStatusMapper;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ConnectionService;

class ExportSubmissionReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private $timezone;
    private $serviceType;
    private $type;

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
        foreach ($data as $datum) {
            $datum->Lead_Source = $this->getLeadSrc($datum->Lead_Source);
            $datum->Lead_Status = GilbertStatusMapper::getStatusAsText ($datum->Lead_Status);
            
            $this->setAgencyName($datum);

            unset($datum->Foxie_Agency_Name);
            unset($datum->Foxie_Agent_Name);

            $this->leadsData[] = $datum;
        }
    }

    private function fetchData(): array
    {
        return DB::table('connection_services as cs')
            ->selectRaw("
                ag.name as `Agency_Name`,
                concat(ap.first_name, ap.last_name) as `Agent_Name`,
                u.email as `Agent_Email`,
                ca.source as `Lead_Source`,
                ca.first_name as `Customer_Firstname`,
                ca.last_name as `Customer_Lastname`,
                CONVERT_TZ(ca.created_at, '+00:00', '+10:00') as `Lead_Created_Date`,
                CONVERT_TZ(ca.moving_date, '+00:00', '+10:00') as `Connection_Date`,
                CONVERT_TZ(cs.submitted_at, '+00:00', '+10:00') as `Lead_Submitted_Date`,
                ca.address_text as `Customer_Address`,
                ca.vendor_id as `Vendor_ID`,
                cs.lead_reference as `Lead_Reference`,
                cs.provider_name as `Utility_Provider`,
                cs.service_type as `Utility_Service`,
                cs.plan_type as `Utility_Plan`,
                cs.status as `Lead_Status`,
                sl.agency_name as `Foxie_Agency_Name`,
                sl.agent_name as `Foxie_Agent_Name`,
                rr.reason_text as `Reason_Text`
            ")
            ->leftJoin('connection_applications as ca', 'cs.connection_application_id', '=', 'ca.id')
            ->leftJoin('agencies as ag', 'ca.agency_id', '=', 'ag.id')
            ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
            ->leftJoin('users as u', function (JoinClause $join) {
                $join->on('ap.id', '=', 'u.profile_id')
                    ->where('profile_type', AgentProfile::class);
            })
            ->leftJoin('suger_leads as sl', 'ca.id', '=', 'sl.connection_application_id')
            ->leftJoin('rejection_reasons as rr', 'cs.id', '=', 'rr.connection_service_id')
            ->where('cs.updated_at', '>=', $this->startDate)
            ->where('cs.updated_at', '<=', $this->endDate)
            ->whereIn('cs.service_type', $this->serviceType)
            ->get()
            ->toArray();
    }

    private function getLeadSrc(?int $src): string
    {
        $res = array_search($src, ConnectionApplication::SOURCE_MAPPING);
        return $res ?: "Unknown";
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
}
