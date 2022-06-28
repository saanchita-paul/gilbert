<?php
namespace App\Services\Agency\Report;

use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Office;
use App\Models\Agency;
use PDF;
use Carbon\Carbon;

class ExportReaCorporateReport
{
    use SetDateRage;

    private string $startDate;
    private string $endDate;
    private string $stringStartDate;
    private string $stringEndDate;
    private string $agencyId;
    private array $corporateReport = [];

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

    private array $cancelledType = [
        ConnectionService::STATUS_CLOSED,
    ];

    public function __construct(string $agencyId, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->agencyId = $agencyId;
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
        Log::info('REA Corporate report', $this->corporateReport);
        
        $agencyName = Agency::find($this->agencyId)->name;

        $data = [
            'agencyName' => $agencyName,
            'startDate' => $this->stringStartDate,
            'endDate' => $this->stringEndDate,
            'report' => $this->corporateReport
        ];
        $pdf = PDF::loadView('pdf.report_corporate', $data);
        return $pdf->inline();

    }

    private function mapData(array $data)
    {
        $officeIds = [];
        $detailedCount = [];
        $totalCount = [
            'total_applications_created' => 0,
            'applications_with_minimum_submitted' => 0,
            'successful_water_connections' => 0,
            'awaiting_confirmation' => 0,
            'cancelled_application' => 0,
            'conversion_rate' => 0,
            'electricityCount' => 0,
            'gasCount' => 0,
            'waterCount' => 0,
            'internetCount' => 0
        ];


        foreach ($data as $datum) {

            $officeIds[] = $datum[0]['office_id'];
            $count = [
                "office_name" => $this->getOfficeName($datum[0]['office_id']),
                "total_applications_created" => count($datum),
                "applications_with_minimum_submitted" => $this->getMinimumSubmitted($datum),
                "successful_water_connections" => $this->getSuccessfulWaterConnection($datum),
                "awaiting_confirmation" => $this->getAwaitingConfirmation($datum),
                "cancelled_application" => $this->getCancelledApplications($datum),
                "electricityCount" => $this->getServiceCount($datum, [ConnectionService::TYPE_ELECTRICITY]),
                "gasCount" => $this->getServiceCount($datum, [ConnectionService::TYPE_GAS]),
                "waterCount" => $this->getServiceCount($datum, [ConnectionService::TYPE_WATER]),
                "internetCount" => $this->getServiceCount($datum, [ConnectionService::TYPE_INTERNET]),
            ];
            $count['conversion_rate'] = $this->getConversionRate($count['total_applications_created'], $count['applications_with_minimum_submitted'], $count['awaiting_confirmation']);
            
            $detailedCount[] = $count;

            $totalCount['total_applications_created'] += $count['total_applications_created'];
            $totalCount['applications_with_minimum_submitted'] += $count['applications_with_minimum_submitted'];
            $totalCount['successful_water_connections'] += $count['successful_water_connections'];
            $totalCount['awaiting_confirmation'] += $count['awaiting_confirmation'];
            $totalCount['cancelled_application'] += $count['cancelled_application'];
            $totalCount['conversion_rate'] = $this->getConversionRate($totalCount['total_applications_created'], $totalCount['applications_with_minimum_submitted'], $totalCount['awaiting_confirmation']);
            $totalCount['electricityCount'] += $count['electricityCount'];
            $totalCount['gasCount'] += $count['gasCount'];
            $totalCount['waterCount'] += $count['waterCount'];
            $totalCount['internetCount'] += $count['internetCount'];
        }
        
        $offices = Office::selectRaw("id, name")
            ->whereNotIn('id', $officeIds)
            ->where('agency_id', $this->agencyId)
            ->get();

        foreach ($offices as $office) {
            $count = [
                "office_name" => $office->name,
                "total_applications_created" => 0,
                "applications_with_minimum_submitted" => 0,
                "successful_water_connections" => 0,
                "awaiting_confirmation" => 0,
                "conversion_rate" => 0,
                "cancelled_application" => 0,
                "electricityCount" => 0,
                "gasCount" => 0,
                "waterCount" => 0,
                "internetCount" => 0,
            ];
            $detailedCount[] = $count;
        }

        $this->corporateReport = [
            "detailedCount" => $detailedCount,
            "totalCount" => $totalCount
        ];
    }

    private function fetchData() : array
    {
        $builder = ConnectionApplication::selectRaw("
            office_id as `office_id`,
            agency_id as `agency_id`,
            id,
            status as `application_status`,
            created_at as `created_date`")
                ->with(['connectionServices' => function ($query) {
                    $query->selectRaw("
                            id as `utility_id`,
                            connection_application_id,
                            status as `utility_status`,
                            service_type as `utility_type`,
                            submitted_at as `submitted_date`
                        ");
                }])
                ->where('created_at', '>=', $this->startDate)
                ->where('created_at', '<=', $this->endDate)
                ->where('office_id', '!=', null)
                ->where('agency_id', $this->agencyId);

        return $builder->get()->groupBy('agency_id')->toArray();
    }

    private function getOfficeName(int $id) : string
    {
        $office = Office::selectRaw("id, name")->where('id', $id)->first();
        return $office->name;
    }

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

    private function getCancelledApplications(array $applications) : int
    {
        $cancelled = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (in_array($service['utility_status'], $this->cancelledType)) {
                    $cancelled++;
                    break;
                }
            }
        }
        return $cancelled;
    }

    private function getConversionRate(int $total, int $minimum, int $awaiting) : float
    {
        $conversionRate = 0;
        if ($total > 0 && ($total - $awaiting > 0)) {
            $conversionRate = ($minimum / ($total - $awaiting)) * 100;
        }
        return round($conversionRate, 1);
    }

    private function getServiceCount(array $applications, array $type) : float
    {
        $count = 0;
        foreach ($applications as $application) {
            foreach ($application['connection_services'] as $service) {
                if (
                    in_array($service['utility_type'], $type)
                ) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }
}
