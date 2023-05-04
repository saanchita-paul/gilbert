<?php

namespace GoogleAds\Services;

use App\Models\CallConversion;
use App\Models\ConnectionApplication;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class SaveCallConversion
{
    /**
     * @var array
     */
    private array $phoneMap = [];

    /**
     * @return void
     */
    public static function save(): void
    {
        (new static())->start();
    }

    /**
     * @return void
     */
    public function start(): void
    {
        $phones = $this->formatPhones($this->getApplications());

        $conversions = (new GenesysConversionDetailsAPI())->searchByPhones($phones);


        CallConversion::query()->insert($this->mapData($conversions));

        $this->savedFetchFailed();

        info("genesys data", ['phones' => $phones, 'failed' => $this->phoneMap]);

    }

    /**
     * @param array $apps
     * @return array
     */
    private function formatPhones(array $apps): array
    {
        $phones = [];
        foreach ($apps as $app) {
            $phone = GenesysConversionDetailsAPI::formatPhoneNumber($app['phone']);
            $this->phoneMap[$phone] = ['phone' => $app['phone'], 'id' => $app['id']];
            $phones[] = $phone;
        }

        return $phones;
    }

    /**
     * @return array
     */
    private function getApplications(): array
    {
        return ConnectionApplication::query()
            ->where('office_id', config('genesys.office_id'))
            ->where(function (Builder $builder) {
                $builder->whereHas('callConversion', function (Builder $conv) {
                    $conv->whereNull('call_start_at');
                })->orWhereDoesntHave('callConversion');
            })
            ->whereDate('created_at', '>=', Carbon::now()->subWeek()->startOfWeek())
            ->select(['id', 'phone'])
            ->orderBy('id', 'desc')
//            ->whereIn('id', [43463])
            // ->limit(50) #todo: update here
            ->get()
            ->toArray();
    }


    /**
     * @param array $data
     * @return array
     */
    private function mapData(array $data): array
    {
        // if (!array_key_exists('conversations', $data)) {
        //     return [];
        // }

        $return = [];

        foreach ($data as $response) {
            $conversations = $response['conversations'] ?? [];

            foreach ($conversations as $conv) {
                try {
                    if (!$session = $conv['participants'][0]['sessions'][0] ?? null) {
                        throw new \Exception("Session not found: conv ID: {$conv['conversationId']}");
                    }
                    $ani = $session['ani'] ?? null;
                    if (
                        !empty($ani) &&
                        (
                            empty($return[$ani]) ||
                            empty($return[$ani]['call_start_at']) ||
                            (!empty($conv['conversationStart']) && Carbon::parse($conv['conversationStart'])->lt($return[$ani]['call_start_at']))
                        )
                    ) {
                        $return[$ani] = [
                            'caller_id' => $this->phoneMap[$ani]['phone'],
                            'call_start_at' => $conv['conversationStart'] ? Carbon::parse($conv['conversationStart']) : null,
                            'call_end_at' => $conv['conversationEnd'] ? Carbon::parse($conv['conversationEnd']) : null,
                            'connection_application_id' => $this->phoneMap[$ani]['id'],
                            'status' => CallConversion::STATUS_FETCHED,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if (isset($this->phoneMap[$ani])) {
                            unset($this->phoneMap[$ani]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('GetConversationDetailService.formatResponseData: ' . $e->getMessage());
                    continue;
                }
            }
        }

        return $return;
    }

    /**
     * @return void
     */
    private function savedFetchFailed(): void
    {
        $failed = [];
        foreach (array_keys($this->phoneMap) as $callerId) {
            $failed[] = [
                'caller_id' => $this->phoneMap[$callerId]['phone'],
                'connection_application_id' => $this->phoneMap[$callerId]['id'],
                'status' => CallConversion::STATUS_FETCH_FAILED,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        CallConversion::query()->insert($failed);
    }
}
