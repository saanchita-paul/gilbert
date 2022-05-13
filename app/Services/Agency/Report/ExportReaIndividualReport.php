<?php
namespace App\Services\Agency\Report;

use App\Models\AgentProfile;
use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use PDF;
use Carbon\Carbon;
use App\Models\Office;

class ExportReaIndividualReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private string $stringStartDate;
    private string $stringEndDate;
    private string $officeId;
    private string $agentId;
    private string $reportType;
    private array $individualReport = [];

    private array $submissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_CANT_CONNECT
    ];

    private array $nonRejectedSubmissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT
    ];

    private array $energyType = [
        ConnectionService::TYPE_ELECTRICITY,
        ConnectionService::TYPE_GAS
    ];

    private array $WaterType = [ConnectionService::TYPE_WATER];

    private array $awaitingConfirmationType = [
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    public function __construct(string $officeId, string $agentId, string $reportType, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->officeId = $officeId;
        $this->agentId = $agentId;
        $this->reportType = $reportType;
        $this->stringStartDate = Carbon::parse($start)->format('M d Y');
        $this->stringEndDate = Carbon::parse($end)->format('M d Y');

    }

    public function run()
    {
        $this->mapData($this->fetchData());
        return $this->export();
    }

    private function export()
    {
        Log::info('REA Individual report', $this->individualReport);

        $officeName = Office::find($this->officeId)->name;
        $agent = AgentProfile::find($this->agentId);
        $agentName = $agent->first_name . ' ' . $agent->last_name;

        $data = [
            'agentName' => $agentName,
            'officeName' => $officeName,
            'startDate' => $this->stringStartDate,
            'endDate' => $this->stringEndDate,
            'report' => $this->individualReport
        ];
        $pdf = PDF::loadView('pdf.report_individual', $data);
        return $pdf->inline();

    }

    private function mapData(array $data)
    {
        $detailedCount = [];
        $totalCount = [
            'total_applications_created' => 0,
            'applications_with_minimum_submitted' => 0,
            'successful_water_connections' => 0,
            'awaiting_confirmation' => 0,
            'conversion_rate' => 0
        ];

        $submittedUtilityCount = [
            'electricity' => 0,
            'gas' => 0,
            'water' => 0,
            'total_energy' => 0,
        ];

        foreach ($data as $datum) {
            $count = [
                "app_id" => $datum['id'],
                "lead_source" => $this->getLeadSource($datum['lead_source']),
                "customer_name" => $datum['customer_first_name'] . ' ' . $datum['customer_last_name'],
                "created_date" => Carbon::parse($datum['created_date'])->format('d/m/y'),
                "connection_date" => Carbon::parse($datum['connection_date'])->format('d/m/y'),
                "full_address" => $datum['full_address'],
                "customer_type" => $this->getCustomerType($datum['customer_type']),
                "is_electricity_submitted" => $this->checkIfUtilitySubmitted($datum['connection_services'], ConnectionService::TYPE_ELECTRICITY),
                "is_gas_submitted" => $this->checkIfUtilitySubmitted($datum['connection_services'], ConnectionService::TYPE_GAS),
                "is_water_submitted" => $this->checkIfUtilitySubmitted($datum['connection_services'], ConnectionService::TYPE_WATER),
            ];
            
            $detailedCount[] = $count;
        }

        $totalCount['total_applications_created'] = count($data);
        $totalCount['applications_with_minimum_submitted'] = $this->getMinimumSubmitted($data);
        $totalCount['successful_water_connections'] = $this->getSuccessfulWaterConnection($data);
        $totalCount['awaiting_confirmation'] = $this->getAwaitingConfirmation($data);
        $totalCount['conversion_rate'] = $this->getConversionRate($totalCount['total_applications_created'], $totalCount['applications_with_minimum_submitted'], $totalCount['awaiting_confirmation']);

        $submittedUtilityCount['electricity'] = $this->getSubmittedUtilityCount($data, ConnectionService::TYPE_ELECTRICITY);
        $submittedUtilityCount['gas'] = $this->getSubmittedUtilityCount($data, ConnectionService::TYPE_GAS);
        $submittedUtilityCount['water'] = $this->getSubmittedUtilityCount($data, ConnectionService::TYPE_WATER);
        $submittedUtilityCount['total_energy'] = $submittedUtilityCount['electricity'] + $submittedUtilityCount['gas'];

        $this->individualReport = [
            "detailedCount" => $detailedCount,
            "totalCount" => $totalCount,
            "submittedUtilityCount" => $submittedUtilityCount
        ];

        Log::info('REA Individual report', $this->individualReport);
    }

    private function fetchData() : array
    {
        $builder = ConnectionApplication::selectRaw("
            office_id as `office_id`,
            created_by as `agent_id`,
            id,
            source as `lead_source`,
            first_name as `customer_first_name`,
            last_name as `customer_last_name`,
            status as `application_status`,
            CONVERT_TZ(created_at, '+00:00', '+10:00') as `created_date`,
            CONVERT_TZ(moving_date, '+00:00', '+10:00') as `connection_date`,
            address_text as `full_address`,
            tenancy_type as `customer_type`
            ")
                ->with(['connectionServices' => function ($query) {
                    $query->selectRaw("
                        id,
                        connection_application_id,
                        status as `utility_status`,
                        service_type as `utility_type`
                    ");
                }])
                ->where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->where('office_id', $this->officeId)
                ->where('created_by', $this->agentId);

        return $builder->get()->toArray();
    }

    private function getLeadSource(?int $src): string
    {
        return ConnectionApplication::SOURCE_NAME_MAPPING[$src] ?? 'null';
    }

    private function getCustomerType(?int $type): string
    {
        return ConnectionApplication::TENANCY_NAME_MAPPING[$type] ?? 'null';
    }

    private function checkIfUtilitySubmitted(?array $services, string $type) : int
    {
        return array_filter($services, function ($service) use ($type) {
            return $service['utility_type'] === $type && in_array($service['utility_status'], $this->nonRejectedSubmissionType);
        })? 1 : 0;
    }

    // private function getUtilityStatus(?array $services, string $type): string
    // {
    //     $utility = array_filter($services, function ($service) use ($type) {
    //         return $service['utility_type'] === $type;
    //     });
    //     return 'Not selected';
    // }

    private function getMinimumSubmitted(array $applications) : int
    {
        $energySubmitted = 0;

        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    in_array($service['utility_type'], $this->energyType) &&
                    in_array($service['utility_status'], $this->submissionType)
                ) {
                    $energySubmitted++;
                    break;
                }
            }
        }
        return $energySubmitted;
    }

    private function getSuccessfulWaterConnection(array $applications) : int
    {
        $waterSubmitted = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    in_array($service['utility_type'], $this->WaterType) &&
                    in_array($service['utility_status'], $this->submissionType)
                ) {
                    $waterSubmitted++;
                    break;
                }
            }
        }
        return $waterSubmitted;
    }

    private function getAwaitingConfirmation(array $applications) : int
    {
        $awaiting = 0;
        foreach ($applications as $application) {
            if(in_array($application['application_status'], $this->awaitingConfirmationType )) {
                $awaiting++;
            }
        }
        return $awaiting;
    }

    private function getConversionRate(int $total, int $minimum, int $awaiting) : float
    {
        $conversionRate = 0;
        if ($total > 0 && ($total - $awaiting > 0)) {
            $conversionRate = ($minimum / ($total - $awaiting)) * 100;
        }
        return round($conversionRate, 1);
    }

    private function getSubmittedUtilityCount(array $applications, string $utilityType) : int
    {
        $count = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    $service['utility_type'] === $utilityType &&
                    in_array($service['utility_status'], $this->nonRejectedSubmissionType)
                ) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }
}
