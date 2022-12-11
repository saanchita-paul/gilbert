<?php

namespace MRI\Services;

use Illuminate\Support\Facades\Http;
use App\Models\MriOffice;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\RequestException;
use MRI\Mail\NotifyNewOfficeMail;
use Illuminate\Support\Facades\Mail;

class GetOfficeService
{
    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    public function getUnregisteredOffices(): array
    {
        $data = MriOffice::whereNull('office_id')->get()->toArray();

        return $data;
    }

    /**
     * Get MRI office key pairs
     */
    public function getData()
    {
        $url = $this->getURL();
        $subKey = empty(config('mri.subscription_key')) ? '574800735b3b4effa9d8ef84d57d345f' : config('mri.subscription_key');

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Ocp-Apim-Subscription-Key" => $subKey
        ])->get($url);

        return json_decode($response->body(), true);
    }

    /**
     * Get MRI office key pairs URL
     *
     * @return string
     */
    private function getURL(): string
    {
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.get_mri_office_key_pairs')) ? '/apikey/v1/application_keys/' : config('mri.endpoints.get_mri_office_key_pairs');
        $appKey = empty(config('mri.app_key')) ? '4e1df42e-5c53-4762-b07a-79f8d731e0bc' : config('mri.app_key');

        return $url . $endpoint . $appKey;
    }

    public function run()
    {
        try {
            $data = $this->getData();
            $savedOfficeNames = $this->saveOffices($data);
            if (!empty($savedOfficeNames)) {
                info(sprintf("Successfully added %s mri offices", count($savedOfficeNames)), ['mri_office_ids' => $savedOfficeNames]);
                $this->notifyNewOffices($savedOfficeNames);
            }
        } catch (RequestException $e) {
            $this->exceptionHandler->addException($e);
        } catch (\Exception $e) {
            $this->exceptionHandler->addException($e);
        }

        if ($this->exceptionHandler->hasExceptions()) {
            $this->exceptionHandler->run();
        }
    }

    private function notifyNewOffices($newOffices)
    {
        $emails = explode(',', config('support_email.agent_not_found')); // default to Georgie
        foreach ($emails as $recipient) {
            if (!empty($recipient)) {
                Mail::to($recipient)->queue(new NotifyNewOfficeMail($newOffices));
            }
        }
    }

    private function saveOffices($officeData)
    {
        $updatedMriOfficeNames = [];
        $filteredData = array_filter($officeData, function ($k) {
            return MriOffice::where('key', $k['key'])->doesntExist();
        });

        foreach ($filteredData as $mriOffice) {
            try {
                $mriOfficeDetails = [
                    'application_id' => !empty(config('mri.app_id')) ? config('mri.app_id') : 'f89d9246-4e4a-437f-a6ba-1940282b097d',
                    'key' => $mriOffice['key'],
                    'company_name' => $mriOffice['company_name'],
                    'activation_date' => $this->formatDate($mriOffice['activation_date'])
                ];
                $newOffice = MriOffice::query()->create($mriOfficeDetails);
                $updatedMriOfficeNames[] = $newOffice->company_name;
            } catch (\Exception $exception) {
                $data = [
                    'officeData' => $mriOffice,
                ];
                $this->exceptionHandler->addException($exception, $data);
            }
        }
        return $updatedMriOfficeNames;
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function formatDate(string $date): ?string
    {
        return Carbon::parse($date)->format("Y-m-d H:i:s") ?? null;
    }
}
