<?php


namespace App\Services\Agency\CafFile;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Filesystem;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class ProcessAndUploadConnectionToSFTPService
{
    const PENDING_PATH = 'ea/pending';

    public function processConnectionData()
    {
        $cafDataProcessService = new CAFDataProcessedService(new \DateTime());

        //Solar Urgent File
        $urgentFile = sprintf('HOOD-Solar-Urgent-Sales-Date-%s.csv', date('dmy'));
        $urgentData = $cafDataProcessService->urgentWithSolar();
        $this->generate($urgentData, $urgentFile);
        unset($urgentData);

        // No Solar Urgent File
        $urgentFile = sprintf('HOOD-Urgent-Sales-Date-%s.csv', date('dmy'));
        $urgentData = $cafDataProcessService->urgentWithoutSolar();
        $this->generate($urgentData, $urgentFile);
        unset($urgentData);

        // Solar Non Urgent File
        $nonUrgentFile = sprintf('HOOD-Solar-Non-Urgent-Sales-Date-%s.csv', date('dmy'));
        $nonUrgentData = $cafDataProcessService->nonUrgentWithSolar();
        $this->generate($nonUrgentData, $nonUrgentFile);
        unset($nonUrgentData);

        //No Solar Non Urgent File
        $nonUrgentFile = sprintf('HOOD-Non-Urgent-Sales-Date-%s.csv', date('dmy'));
        $nonUrgentData = $cafDataProcessService->nonUrgentWithoutSolar();
        $this->generate($nonUrgentData, $nonUrgentFile);
        unset($nonUrgentData);

        // Urgent File QLD when Power is Off
        $urgentQldFile = sprintf('HOOD-QLD-VI-Urgent-Sales-Date-%s.csv', date('dmy'));
        $urgentQldData = $cafDataProcessService->urgentQld();
        $this->generate($urgentQldData, $urgentQldFile);
        unset($urgentQldData);

        // Non Urgent File QLD when Power is Off
        $nonUrgentQldFile = sprintf('HOOD-QLD-VI-Non-Urgent-Sales-Date-%s.csv', date('dmy'));
        $nonUrgentQldData = $cafDataProcessService->urgentQld();
        $this->generate($nonUrgentQldData, $nonUrgentQldFile);
        unset($nonUrgentQldData);





    }

    private function generate(Collection $collection, $path)
    {
        try {
            $exporter = new CAFDataMappingService($collection);

            if (!$exporter->hasData()) {
                $this->warn(sprintf('There is no data found to sent EnergyAustralia for %s', $path));
                return 0;
            }

            $path = self::PENDING_PATH. DIRECTORY_SEPARATOR . $path;

            Excel::store($exporter, $path);
            $exporter->updateRecords();

            //disable upload
//            $this->uploadCSVDataToSFTP($path);

            Log::info(sprintf('%s file exported to EA', $path));

        } catch (\Exception $exception) {
            Log::error("[ProcessAndUploadConnectionToSFTPService:generate] ->  " .$exception->getMessage());
            Log::error($exception->getTraceAsString());
        } finally {
//            $this->deleteLocalGeneratedCSVFiles($path);
        }

    }

    private function uploadCSVDataToSFTP($path)
    {
//        $filesystem = new Filesystem($this->createAdapter());
        $fullPath = storage_path('app/public/' . $path);
        $stream = fopen($fullPath, 'r');
        try {
//            $filesystem->writeStream($path, $stream);
            $fullPaths[] = $fullPath;
        } catch (\Exception $e) {
            \Log::error("Failed to upload csv: " . $e->getTraceAsString());
        } finally {
            fclose($stream);
        }
    }

    private function warn($string, $verbosity = null)
    {
        Log::info($string);
    }

    private function createAdapter()
    {
//        return new SftpAdapter([
//            'host' => env('EA_SFTP_HOST'),
//            'port' => env('EA_SFTP_PORT', 22),
//            'username' => env('EA_SFTP_USERNAME', 'forge'),
//            // 'privateKey' => env('EA_SFTP_PRIVATE_KEY', './private_keys/id_rsa_hood_2'),
//            'password' => 'HOOD_Test@2021',
//            'root' => env('EA_SFTP_ROOT', '/home/forge/test_sftp/'),
//            'timeout' => 10,
//            'directoryPerm' => 0755,
//        ]);
    }

    private function deleteLocalGeneratedCSVFiles($path)
    {
        if (\Storage::exists($path)) {
            \Storage::delete($path);
        }
    }

}
