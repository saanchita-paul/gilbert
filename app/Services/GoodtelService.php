<?php

namespace App\Services;

use App\Mail\InternetPaymentLinkMail;
use App\Models\GoodtelPlan;
use App\Models\GoodtelPlanPaymentLink;
use App\Models\InternetServiceInfo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GoodtelService
{
    /**
     * Get all active plans and payment links
     *
     * @return Collection
     */
    public function getPlans(): Collection
    {
        return GoodtelPlan::where('is_active', true)->with(['paymentLinks'])->get();
    }

    public static function getModems(): array
    {
        return GoodtelPlanPaymentLink::query()
            ->selectRaw("distinct(modem_type) as value, modem_text as text")
            ->get()
            ->toArray();
    }

    /**
     * Update or create plans and payment links
     *
     * @param array $plans
     * @return void
     * @throws \Throwable
     */
    public function updateOrCreatePlans(array $plans): void
    {
        try {
            DB::beginTransaction();

            // loop through each plan and update or create it and if dbs plans are not in the array, set them to inactive
            foreach ($plans as $plan) {
                // update or create the plan
                $createdPlan = GoodtelPlan::updateOrCreate(
                    ['name' => $plan['name']],
                    [
                        'display_name' => $plan['display_name'],
                        'type' => $plan['type'],
                        'price' => $plan['price'],
                        'details_url' => $plan['details_url'],
                        'mbps' => $plan['mbps'],
                        'is_active' => true,
                        'caf_plan_name' => $plan['caf_plan_name']
                    ]
                );

                // loop through each payment link and update or create it
                foreach ($plan['payment_links'] as $paymentLink) {
                    GoodtelPlanPaymentLink::updateOrCreate(
                        [
                            'goodtel_plan_id' => $createdPlan->id,
                            'modem_type' => $paymentLink['modem_type']
                        ],
                        [
                            'modem_text' => $paymentLink['modem_text'],
                            'modem_price' => $paymentLink['modem_price'],
                            'payment_link' => $paymentLink['payment_link'],
                            'is_active' => true
                        ]
                    );
                }
            }


            // set all plans that are not in the array to inactive and their payment links to inactive
            GoodtelPlan::whereNotIn('name', array_column($plans, 'name'))->update(['is_active' => false]);
            GoodtelPlanPaymentLink::whereNotIn(
                'goodtel_plan_id',
                GoodtelPlan::whereIn('name', array_column($plans, 'name'))->pluck('id')
            )->update(['is_active' => false]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $appId
     * @return void
     * @throws \Exception
     */
    public function paymentLinkSend(int $appId): void
    {
        try {
            $internetServiceInfo = InternetServiceInfo::where('connection_application_id', $appId)->first();
            if ($internetServiceInfo->modem_type === 'standard' || $internetServiceInfo->modem_type === 'upgraded') {
                $paymentLink = GoodtelPlanPaymentLink::where([
                    ['goodtel_plan_id', $internetServiceInfo->goodtel_plan_id],
                    ['modem_type', $internetServiceInfo->modem_type]
                ])->first();
            } else {
                $paymentLink = GoodtelPlanPaymentLink::where([
                    ['goodtel_plan_id', $internetServiceInfo->goodtel_plan_id],
                    ['modem_type', 'none']
                ])->first();
            }

            Mail::to($internetServiceInfo->connectionApplication->email)->send(
                new InternetPaymentLinkMail([
                    'customer_name' => $internetServiceInfo->connectionApplication->first_name,
                    'payment_url' => $paymentLink->payment_link,
                    'charity' => InternetServiceInfo::CHARITY_MAPPER[$internetServiceInfo->charity] ?? null,
                    'service_address' => $internetServiceInfo->connectionApplication->address_text ?? null,
                    'plan_name' => $internetServiceInfo->goodtelPlan->getPlanName() ?? null,
                    'modem_type' => $this->getModemType($internetServiceInfo->modem_type)
                ])
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getModemType($modemType)
    {
        return strtolower($modemType) === 'none' ? 'BYO' : ucfirst($modemType) ?? null;
    }
}
