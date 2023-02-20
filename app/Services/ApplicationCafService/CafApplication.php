<?php

namespace App\Services\ApplicationCafService;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;
use Illuminate\Filesystem\Filesystem;

class CafApplication
{
    const PENDING_PATH = 'gilbert';
    private mixed $chatbotUri;
    private array $applicationIdList;

    private array $gasPromotionData;
    private array $elePromotionData;

    private $powerShopApplicationList = [];

    private  $originApplicationList = [];


    public function __construct(array $applicationIdList){
        $this->applicationIdList = $applicationIdList;

        $this->fetchApplications();
        $this->clearExistingCafData();
    }

    private function fetchApplications(): void {
        $this->fetchPSApplication();
        $this->fetchOriginApplication();
    }

    public function prepareCafFileData():void{
        $urgentFile = sprintf('HOOD-Powershop-Sales-Date-%s.csv', $this->getCAFPostfix());
        $this->generate($this->powerShopApplicationList ,$urgentFile, ConnectionService::PROVIDER_POWER_SHOP);

        $urgentFile = sprintf('HOOD-Origin-Sales-Date-%s.csv', $this->getCAFPostfix());
        $this->generate($this->originApplicationList ,$urgentFile, ConnectionService::PROVIDER_ORIGIN);
    }

    private function getCAFPostfix(): string{
        return now(10)->format('dmy_H_i_s');
    }


    public function generate($data, string $path, $provider): void{
         try {
             switch ($provider){
                 case ConnectionService::PROVIDER_POWER_SHOP:
                     $exporter = new PowerShopExporter($data);
                     break;
                 case ConnectionService::PROVIDER_ORIGIN:
                     $exporter = new OriginExporter($data);
                     break;
                 default:
                     Log::warning(sprintf('Invalid Provider to generate CAF'));
             }

             $currentTime = now(10)->format('d-m-Y');
             $storagePath = storage_path("app/public/gilbert/".$currentTime);
             if(!File::isDirectory($storagePath)){
                 File::makeDirectory($storagePath);
             }

             $path = self::PENDING_PATH. DIRECTORY_SEPARATOR.  $currentTime. DIRECTORY_SEPARATOR. $path;


            $data = $exporter->getCollection();
            $fastExcel = new FastExcel();
            $fastExcel->data($data);
            $fastExcel->export(storage_path("app/public/$path"));
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




    public function downloadedZipFile(){
        $currentDate = now(10)->format('d-m-Y');
        $zipName = now(10)->unix();
        $dataDir =  self::PENDING_PATH. DIRECTORY_SEPARATOR.$currentDate;

        $storagePath = storage_path("app/public/gilbert/data");
        if(!File::isDirectory($storagePath)){
            File::makeDirectory($storagePath);
        }

        $subPath = "gilbert/data/$zipName.zip";
        $zipFilePath =  storage_path("app/public/$subPath");


        $zip = new \ZipArchive();

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
            $fileDir = storage_path('app/public/'.$dataDir);


            if(is_dir($fileDir)) {
                $files = File::files(storage_path('app/public/'.$dataDir));

                foreach ( $files as $key=>$file)
                {
                    $zip->addFile($file, basename($file));
                }
            }
            $zip->close();
        } else {
            throw new \Exception("Error while caf downloading");
        }

        return $subPath;
    }

    private function clearExistingCafData(){
//        Storage::download("robots.txt");
        try {
            $removedOldService = new RemoveOldCafService();
            $removedOldService->delete(true);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

    }

}
