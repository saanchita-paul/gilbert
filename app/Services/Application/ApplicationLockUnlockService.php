<?php

namespace App\Services\Application;

use App\Models\ConnectionApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ApplicationLockUnlockService
{

    private $applicaiton;

    public function __construct($applicaitonId)
    {
        $this->applicaiton = ConnectionApplication::find($applicaitonId);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function setStatus(Request $request)
    {
        if ($request->has('is_locked')) {
            $this->lockUnlockChatbotApp($request);
        }
        return $this->applicaiton;
    }

    /**
     * @throws \Exception
     */
    public function lockUnlockChatbotApp(Request $request)
    {
        $url = config('bot.root_url') . '/g2cb/api/applications/' . $this->applicaiton->chatbot_id . '/lock-unlock-app';
        $response = Http::post($url, [
            'is_locked' => !$request->is_locked,
        ]);
        if ($response->status() == 200) {
            $this->applicaiton->update(['is_locked' => $request->get('is_locked')]);
        } else {
            \Log::error(json_encode($response->body()));
            throw new \Exception('Lock/unlock unsuccessful.');
        }
    }

    /**
     * @throws \Exception
     */
    public function closeOrEscalatedChatbot(): bool
    {
        if ($this->applicaiton->status != 8 && $this->applicaiton->status != 3) {
            return false;
        }

        $data = [
            'is_closed' => false,
            'is_escalated' => true,
        ];
        if ($this->applicaiton->status == 8) {
            $data = [
                'is_closed' => true,
                'is_escalated' => false,
            ];
        }

        $url = config('bot.root_url') . '/g2cb/api/applications/' .
            $this->applicaiton->chatbot_id . '/closed-or-escalated';
        $response = Http::post($url, $data);

        if ($response->status() != 200) {
            \Log::error(json_encode($response->body()));
            throw new \Exception('Close or escalated unsuccessful.');
        }
        return true;
    }
}
