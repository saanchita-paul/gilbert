<?php

namespace App\Modules\NBN\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\InternetServiceInfo;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NBNCafGenerationService
{

    private array $applicationIdList;
    private $applicationList;
    private array $mappedApplicationList;


    public function __construct(array $applicationIdList)
    {
        $this->applicationIdList = $applicationIdList;
        $this->fetchApplications();
        $this->mapApplications();
    }

    /**
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     * @throws InvalidArgumentException
     * @throws IOException
     */
    public function downloadCAF(): StreamedResponse
    {
        return FastExcel::data(collect($this->mappedApplicationList))->download(now()->unix() . '.xlsx');
    }

    private function fetchApplications(): void
    {
        $this->applicationList = ConnectionApplication::query()->whereIn('id', $this->applicationIdList)
            ->with('connectionServices', 'identification', 'internetServiceInfo')
            ->get();
//        dd($this->applicationList);
    }

    public function mapApplications()
    {
        $selectedId = [];

        foreach ($this->applicationList as $app) {
//            dd($app->connectionServices);
            try {
//                $cafToken = $this->getPowerShopCafToken($app);
                $this->mappedApplicationList[] = [
                    'Title' => $app->title,
                    'First Name' => $app->first_name,
                    'Last Name' => $app->last_name,
                    'Email Address' => $app->email,
                    'Mobile Number' => $app->phone,
                    'Phone Number' => $app->homephone,
                    'Sales Agent' => 'Hood',
                    'Connection Address 1' => $this->generateConnectionAddress($app),
                    'Connection Address 2' => $app->street_type,
                    'Connection Address City' => $app->city,
                    'Connection Address State' => $app->state,
                    'Connection Address Postcode' => $app->postcode,
                    'Shipping Address 1' => $this->generateShippingAddress($app),
                    'Shipping Address 2' => $app->internetServiceInfo->street_type,
                    'Shipping Address City' => $app->internetServiceInfo->city,
                    'Shipping Address State' => $app->internetServiceInfo->state,
                    'Shipping Address Postcode' => $app->internetServiceInfo->postcode,
                    'Billing Address 1' => $this->generateBillingAddress($app),
                    'Billing Address 2' => $app->billing_street_type,
                    'Billing Address City' => $app->billing_city,
                    'Billing Address State' => $app->billing_state,
                    'Billing Address Postcode' => $app->billing_postcode,
                    'Business Name' => '',
                    'Business ABN' => null,
                    'Account Password' => $app->internetServiceInfo->otp,
                    'Driving Licence Number' => $app->identification?->card_number,
                    'State of Issue' => $app->identification?->state,
                    'Date of Birth' => $this->generateDate($app->dob),
                    'Preferred Connection Date' => $this->generateDate($app->moving_date),
                    'Plan Variant' => ucfirst($app->internetServiceInfo->goodtelPlan->type),
                    'Utility Bill Plan Name' => $this->generateUtilityBill($app->internetServiceInfo->goodtelPlan->display_name),
                    'Phone calls Y/N' => $this->generateReadableAnswer($app->internetServiceInfo->is_need_home_phone),
                    'Phone Number to Transfer' => $app->internetServiceInfo->home_phone_number,
                    'Name of Current Provider' => $app->internetServiceInfo->current_provider,
                    'Account Number of Current Provider' => $app->internetServiceInfo->account_number,
                    'BYO Modem Y/N' => $this->isBYOModem($app->internetServiceInfo->modem_type),
                    'Modem Type' => InternetServiceInfo::MODEM_MAPPER[$app->internetServiceInfo->modem_type],
                    'Amount Paid' => '',
                    'Agreed to Policies' => 'Y',
                    'Back to Base  Alarm Y/N' => $this->generateReadableAnswer($app->internetServiceInfo->is_back_to_base),
                    'Medical Alarm Y/N' => $this->generateReadableAnswer($app->internetServiceInfo->is_security_alarm),
                    'Selected Charity' => InternetServiceInfo::CHARITY_MAPPER[$app->internetServiceInfo->charity],
                    'Stripe PaymentId' => '',
                    'Stripe CustomerId' => ''
                ];
                $selectedId[] = $app->id;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                info('Data failed to export due to', [$exception->getMessage()]);

            }

        }
        InternetServiceInfo::whereIn('connection_application_id', $selectedId)
            ->update(['is_caf_generated' => true]);
    }

    private function generateConnectionAddress($app): string
    {
        return "{$app->unit_number} {$app->street_number} {$app->street_name}";
    }

    private function generateBillingAddress($app): string
    {
        $unit = "U".$app->billing_unit_number;
        return "{$unit} {$app->billing_street_number} {$app->billing_street_name_only}";
    }

    private function generateShippingAddress($app): string
    {
        return "{$app->internetServiceInfo->unit_number} {$app->internetServiceInfo->street_number} {$app->internetServiceInfo->street_name_only}";
    }

    private function generateDate($date): string
    {
        return date('m/d/Y', strtotime($date));
    }

    private function generateReadableAnswer($bool): string
    {
        return $bool ? 'Y' : 'N';
    }

    private function isBYOModem($modem_type): string
    {
        if ($modem_type === 'byo') return 'Y';
        return 'N';
    }

    private function generateUtilityBill($plan): string
    {
        $plan = strtolower($plan);
        return ConnectionService::NBN_UTILITY_BILL_PLANS[$plan];
    }


}
