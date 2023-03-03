<?php

namespace Database\Seeders;

use App\Models\GoodtelPlan;
use App\Models\GoodtelPlanPaymentLink;
use App\Services\GoodtelService;
use Illuminate\Database\Seeder;

class GoodtelPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        $plans = [
            [
                'display_name' => 'Casual- nbn 25/10Mbps',
                'name' => 'casual_nbn25',
                'type' => 'casual',
                'price' => 68,
                'details_url' => 'https://www.goodtel.com.au/assets/downloads/nbn%E2%84%A22510-mobile-bundle-Critical-Information-Summary-4_2022-11-30-045642_fdha.pdf',
                'payment_links' => [
                    [
                        'modem_type' => 'none',
                        'payment_link' => 'https://buy.stripe.com/6oE7uLepm0wh0GA9B6'
                    ],
                    [
                        'modem_type' => 'standard',
                        'payment_link' => 'https://buy.stripe.com/dR62ar9521Al0GAdRe'
                    ],
                    [
                        'modem_type' => 'upgraded',
                        'payment_link' => 'https://buy.stripe.com/fZe6qH5SQa6RahaaF6'
                    ],
                ]
            ],
            [
                'display_name' => 'Family-nbn 50/20 Mbps',
                'name' => 'family_nbn50',
                'type' => 'family',
                'price' => 78,
                'details_url' => 'https://www.goodtel.com.au/assets/downloads/nbn%E2%84%A25020-nbn%E2%84%A2-Critical-Information-Summary-7.pdf',
                'payment_links' => [
                    [
                        'modem_type' => 'none',
                        'payment_link' => 'https://buy.stripe.com/28oaGXgxuen7gFyeVr',
                    ],
                    [
                        'modem_type' => 'standard',
                        'payment_link' => 'https://buy.stripe.com/3cs2ara96frb2OIfZn',
                    ],
                    [
                        'modem_type' => 'upgraded',
                        'payment_link' => 'https://buy.stripe.com/4gwaGXbda6UF8926oR',
                    ]
                ]
            ],
            [
                'display_name' => 'Superfast- nbn 100/25 Mbps',
                'name' => 'superfast_nbn100',
                'type' => 'superfast',
                'price' => 98,
                'details_url' => 'https://www.goodtel.com.au/assets/downloads/nbn%E2%84%A210020-Critical-Information-Summary-5.pdf',
                'payment_links' => [
                    [
                        'modem_type' => 'none',
                        'payment_link' => 'https://buy.stripe.com/fZe9CT2GE1Al9d6eVs',
                    ],
                    [
                        'modem_type' => 'standard',
                        'payment_link' => 'https://buy.stripe.com/aEUdT93KIfrb892fZp',
                    ],
                    [
                        'modem_type' => 'upgraded',
                        'payment_link' => 'https://buy.stripe.com/dR64iz3KIgvf4WQcNg',
                    ]
                ]
            ],
            [
                'display_name' => 'Blazing- nbn 250/25 Mbps',
                'name' => 'blazing_nbn250',
                'type' => 'blazing',
                'price' => 128,
                'details_url' => 'https://www.goodtel.com.au/assets/downloads/nbn%E2%84%A225025-Critical-Information-Summary-4.pdf',
                'payment_links' => [
                    [
                        'modem_type' => 'none',
                        'payment_link' => 'https://buy.stripe.com/6oE7uLche2Ep2OI14D',
                    ],
                    [
                        'modem_type' => 'standard',
                        'payment_link' => 'https://buy.stripe.com/3cseXdepm6UF60UfZo',
                    ],
                    [
                        'modem_type' => 'upgraded',
                        'payment_link' => 'https://buy.stripe.com/cN2cP5cheen74WQ8x1',
                    ]
                ]
            ]
        ];

        $service = new GoodtelService();
        $service->updateOrCreatePlans($plans);

        $this->command->info('GoodTel plans seeded!');
    }
}
