<?php

namespace App\Services\ApplicationCafService;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class CafApplication
{
    const PENDING_PATH = 'gilbert';
    private mixed $chatbotUri;
    private array $applicationIdList;

    private array $gasPromotionData;
    private array $elePromotionData;

    private $powerShopApplicationList = [];

    private  $originApplicationList = [];


    public function __construct(array $applicationIdList)
    {
        $this->chatbotUri = config('bot.root_url');
        $this->getPromotionCode();
        $this->applicationIdList = $applicationIdList;
        // $this->cafToken = $this->getPowerShopCafToken();

        $this->fetchApplications();
//        $this->mapApplications();
    }

    private function fetchApplications(): void {
        $this->fetchPSApplication();
        $this->fetchOriginApplication();

    }

    public function prepareCafFileData():void{
        $urgentFile = sprintf('HOOD-Powershop-Sales-Date-%s.csv', $this->getCAFPostfix());
        $this->generate(collect($this->powerShopApplicationList) ,$urgentFile, ConnectionService::PROVIDER_POWER_SHOP);

        $urgentFile = sprintf('HOOD-Origin-Sales-Date-%s.csv', $this->getCAFPostfix());
        $this->generate(collect($this->originApplicationList) ,$urgentFile, ConnectionService::PROVIDER_POWER_SHOP);
    }

    private function getCAFPostfix(): string{
        return now(10)->format('dmy_H_i_s');
    }


    public function generate($collection, string $path, $provider): void{
         try {
             switch ($provider){
                 case ConnectionService::PROVIDER_POWER_SHOP:
//                     $exporter = new ConnectionApplicationExporter($collection);
                     break;
                 case ConnectionService::PROVIDER_ORIGIN:
//                     $exporter = new OriginExporter($collection);
                     break;
                 default:
                     Log::warning(sprintf('Invalid Provider to generate CAF'));
             }

//             if (!$exporter->hasData()) {
//                 $this->warn(sprintf('There is no data found to sent EnergyAustralia for %s', $path));
//                 return 0;
//             }
             $path = self::PENDING_PATH. DIRECTORY_SEPARATOR.  now(10)->format('d-m-Y'). DIRECTORY_SEPARATOR. $path;
             (new FastExcel())->data($collection)->export($path);
//             $exporter->updateServiceRecords();

         } catch (\Exception $exception) {
             Log::error($exception->getTraceAsString());
         }
     }



    private function fetchPSApplication(): void {
        $this->powerShopApplicationList = ConnectionApplication::query()->whereIn('id', $this->applicationIdList)
            ->whereHas('connectionServices', function($query) {
                $query->where('provider_name', 'powershop');
            })
            ->with('connectionServices','identification','authorizedPerson', 'powershopPaymentInfo')
            ->get();
    }


    private function fetchOriginApplication(): void {
        $this->originApplicationList = ConnectionApplication::query()->whereIn('id', $this->applicationIdList)
            ->whereHas('connectionServices', function($query) {
                $query->where('provider_name', 'origin');
            })
            ->with('connectionServices','identification','authorizedPerson', 'powershopPaymentInfo')
            ->get();
    }


    private function getPromotionCode(){
        try {
            $url = $this->chatbotUri.'/hood-dashboard/api/power-shop/promo-code';

            if (config('app.env') == 'local') $response = Http::withOptions(['verify' => false,])->get($url);
            else $response = Http::get($url);

            if($response->status() == 200) {
                $this->mapPromotionCode(json_decode($response->body(), true));
            }
        } catch (\Exception $e) {
            Log::warning('No promotion code is found'.$e->getMessage());
        }
    }

    private function mapPromotionCode(?array $promotionData): void
    {
        $this->gasPromotionData = data_get($promotionData, 'gas', []);
        $this->elePromotionData = data_get($promotionData, 'electricity', []);
    }

}
