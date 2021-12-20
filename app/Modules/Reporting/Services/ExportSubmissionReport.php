<?php
namespace App\Modules\Reporting\Services;

use App\Models\AgentProfile;
use App\Models\ConnectionApplication;
use App\Models\HoodProfile;
use App\Services\Utility\GilbertStatusMapper;
use Carbon\Carbon;
use DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportSubmissionReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private $timezone;

    public function __construct(string $start, string $end)
    {
        $this->setDateRange($start, $end);
    }

    private array $leadsData = [];

    public function run()
    {
        $this->mapData($this->fetchData());
        return $this->export();
    }

    private function export()
    {
//        return  $this->leadsData;
        $name = now()->format('U') . '.csv';
        return (new FastExcel($this->leadsData))->download($name);
    }

    private function mapData(array $data)
    {
        foreach ($data as $datum) {
            $datum->Lead_Source = $this->getLeadSrc($datum->Lead_Source);
            $datum->Lead_Status = GilbertStatusMapper::getStatusAsText ($datum->Lead_Status);
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
                cs.status as `Lead_Status`
            ")
            ->leftJoin('connection_applications as ca', 'cs.connection_application_id', '=', 'ca.id')
            ->leftJoin('agencies as ag', 'ca.agency_id', '=', 'ag.id')
            ->leftJoin('agent_profiles as ap', 'ap.id', '=', 'ca.created_by')
            ->leftJoin('users as u', 'ap.id', '=', 'u.profile_id')
            ->where('u.profile_type',  '!=', HoodProfile::class)
            ->where('cs.updated_at', '>=', $this->startDate)
            ->where('cs.updated_at', '<=', $this->endDate)
            ->get()
            ->toArray();
    }

    private function getLeadSrc(?int $src): string
    {
        $res = array_search($src, ConnectionApplication::SOURCE_MAPPING);
        return $res ?: "Unknown";
    }
}
