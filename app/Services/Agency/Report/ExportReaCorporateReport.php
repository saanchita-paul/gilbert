<?php
namespace App\Services\Agency\Report;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use App\Modules\Reporting\Services\SetDateRage;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\Office;
use App\Models\Agency;
use PDF;
use Carbon\Carbon;

/**
 *
 */
class ExportReaCorporateReport
{
    use SetDateRage;

    /**
     * @var string
     */
    private string $startDate;
    /**
     * @var string
     */
    private string $endDate;
    /**
     * @var string
     */
    private string $stringStartDate;
    /**
     * @var string
     */
    private string $stringEndDate;
    /**
     * @var string
     */
    private string $agencyId;
    /**
     * @var array
     */
    private array $corporateReport = [];


    /**
     *
     */
    private const SUBMITTED_STATUSES = [
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::WATER_STATUS_CONNECTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::AC_MANUAL_PROCESSING,
    ];

    /**
     * @var array
     */
    private array $submissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_REJECTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT,
        ConnectionService::STATUS_CANT_CONNECT
    ];

    /**
     * @var array
     */
    private array $nonRejectedSubmissionType = [
        ConnectionService::STATUS_SUBMITTED,
        ConnectionService::STATUS_ACCEPTED,
        ConnectionService::STATUS_CLOSED,
        ConnectionService::AC_MANUAL_PROCESSING,
        ConnectionService::STATUS_ENERGY_SUBMIT
    ];

    /**
     * @var array
     */
    private array $energyType = [
        ConnectionService::TYPE_ELECTRICITY,
        ConnectionService::TYPE_GAS
    ];

    /**
     * @var array
     */
    private array $WaterType = [ConnectionService::TYPE_WATER];

    /**
     * @var array
     */
    private array $awaitingConfirmationType = [
        ConnectionApplication::STATUS_UNASSIGNED,
        ConnectionApplication::STATUS_ASSIGNED,
        ConnectionApplication::STATUS_ESCALATED
    ];

    /**
     * @var array
     */
    private array $cancelledType = [
        ConnectionService::STATUS_CLOSED,
    ];

    /**
     * @param string $agencyId
     * @param string $start
     * @param string $end
     */
    public function __construct(string $agencyId, string $start, string $end)
    {
        $this->setDateRange($start, $end);
        $this->agencyId = $agencyId;
        $this->stringStartDate = Carbon::parse($start)->format('M d Y');
        $this->stringEndDate = Carbon::parse($end)->format('M d Y');

    }

    /**
     * @return mixed
     */
    public function run()
    {
        $officeReport = $this->fetchOfficeReport();
        $this->corporateReport = [
            "detailedCount" => $this->calculateConversionRate($officeReport),
            "totalCount" => $this->calculateTotal($officeReport)
        ];
        return $this->export();
    }

    /**
     * @return mixed
     */
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

    /**
     * @return array
     */
    private function fetchOfficeReport(): array
    {
        $canceledStatus = ConnectionApplication::STATUS_CLOSED;


        $res = \DB::table('offices as ofc')
            ->selectSub($this->getAwaitBuilder(), 'awaitingConfirmation')
            ->selectSub($this->getEnergyServiceSubmittedBuilder(), 'energyServiceSubmitted')
            ->selectSub($this->getServiceBuilder(ConnectionService::TYPE_ELECTRICITY), 'electricityCount')
            ->selectSub($this->getServiceBuilder(ConnectionService::TYPE_GAS), 'gasCount')
            ->selectSub($this->getServiceBuilder(ConnectionService::TYPE_WATER), 'waterCount')
            ->selectRaw("
                ofc.id,
                ofc.name as officeName,
                (select count(ca.id) from connection_applications as ca where ca.office_id=ofc.id and ca.created_at >= '$this->startDate' and ca.created_at <= '$this->endDate') as totalApplicationCreated,
                (select count(ca.id) from connection_applications as ca where ca.office_id=ofc.id and ca.status = $canceledStatus) as cancelledCount
            ")
            ->where('agency_id', $this->agencyId)->get();

        return $res->toArray();
    }

    /**
     * @param array $officeReports
     * @return array
     */
    private function calculateConversionRate(array $officeReports): array
    {
        foreach ($officeReports as $index => $report) {
            if (data_get($report, 'energyServiceSubmitted') === 0 || data_get($report, 'totalApplicationCreated') === 0) {
                data_set($officeReports, "$index.conversionRate", 0);
            } else {
                $rate = data_get($report, 'energyServiceSubmitted')
                    / (data_get($report, 'totalApplicationCreated') - data_get($report, 'awaitingConfirmation'))
                    * 100;
                data_set($officeReports, "$index.conversionRate", round($rate, 2));
            }
        }

        return  $officeReports;
    }

    private function calculateTotal(array $officeReports): array
    {
        $total = [
            'energyServiceSubmitted' => 0,
            'awaitingConfirmation' => 0,
            'conversionRate' => 0,
            'totalApplicationCreated' => 0,
            'electricityCount' => 0,
            'gasCount' => 0,
            'waterCount' => 0,
            'cancelledCount' => 0,
        ];
        foreach ($officeReports as $report) {
            $total['energyServiceSubmitted'] += data_get($report, 'energyServiceSubmitted');
            $total['awaitingConfirmation'] += data_get($report, 'awaitingConfirmation');
            $total['conversionRate'] += data_get($report, 'conversionRate');
            $total['totalApplicationCreated'] += data_get($report, 'totalApplicationCreated');
            $total['electricityCount'] += data_get($report, 'electricityCount');
            $total['gasCount'] += data_get($report, 'gasCount');
            $total['waterCount'] += data_get($report, 'waterCount');
            $total['cancelledCount'] += data_get($report, 'cancelledCount');
        }

        $total['conversionRate'] = round($total['conversionRate'] / count($officeReports), 2);

        return $total;
    }


    /**
     * @param string $serviceType
     * @param array $statuses
     * @return Builder
     */
    private function getServiceBuilder(string $serviceType, array $statuses = self::SUBMITTED_STATUSES): Builder
    {
        $builder = \DB::table('connection_services as cs')
            ->selectRaw('count(*)')
            ->join('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
            ->whereRaw('ofc.id = ca.office_id')
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate)
            ->whereRaw("cs.service_type='$serviceType'");

        if ($statuses) {
            $builder->whereIn('cs.status', $statuses);
        }

        return $builder;
    }

    /**
     * @return Builder
     */
    private function getAwaitBuilder(): Builder
    {
        return \DB::table('connection_applications as ca')
            ->selectRaw('count(ca.id)')
            ->whereIn('ca.status', $this->awaitingConfirmationType)
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate)
            ->whereRaw('ofc.id = ca.office_id');
    }

    /**
     * @return Builder
     */
    private function getEnergyServiceSubmittedBuilder(): Builder
    {
        return \DB::table('connection_services as cs')
            ->selectRaw('count(distinct ca.id)')
            ->whereIn('cs.service_type', [ConnectionService::TYPE_GAS, ConnectionService::TYPE_ELECTRICITY])
            ->join('connection_applications as ca', 'ca.id', '=', 'cs.connection_application_id')
            ->whereIn('cs.status', self::SUBMITTED_STATUSES)
            ->where('ca.created_at', '>=', $this->startDate)
            ->where('ca.created_at', '<=', $this->endDate)
            ->whereRaw('ofc.id = ca.office_id');
    }
}
