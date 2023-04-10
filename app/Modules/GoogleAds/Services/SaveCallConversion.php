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

        info("genesys data", ['phones' => $phones, 'response' => $conversions, 'failed' => $this->phoneMap]);

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
            $this->phoneMap[$phone] = $app['id'];
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
            ->select(['id', 'phone'])
            ->orderBy('id', 'desc')
            ->limit(51)
            ->get()
            ->toArray();
    }


    /**
     * @param array $data
     * @return array
     */
    private function mapData(array $data): array
    {
        if (!array_key_exists('conversations', $data)) {
            return [];
        }

        $return = [];

        $conversations = $data['conversations'];

        foreach ($conversations as $conv) {
            try {
                if (!$session = $conv['participants'][0]['sessions'][0] ?? null) {
                    throw new \Exception("Session not found: conv ID: {$conv['conversationId']}");
                }
                $ani = $session['ani'] ?? null;
                if (isset($this->phoneMap[$ani])) {
                    $return[] = [
                        'caller_id' => $ani,
                        'call_start_at' => $conv['conversationStart'] ? Carbon::parse($conv['conversationStart']) : null,
                        'call_end_at' => $conv['conversationEnd'] ? Carbon::parse($conv['conversationEnd']) : null,
                        'connection_application_id' => $this->phoneMap[$ani],
                        'status' => CallConversion::STATUS_FETCHED,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    unset($this->phoneMap[$ani]);
                }
            } catch (\Exception $e) {
                \Log::error('GetConversationDetailService.formatResponseData: ' . $e->getMessage());
                continue;
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
                'caller_id' => $callerId,
                'connection_application_id' => $this->phoneMap[$callerId],
                'status' => CallConversion::STATUS_FETCH_FAILED,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        CallConversion::query()->insert($failed);
    }
}
