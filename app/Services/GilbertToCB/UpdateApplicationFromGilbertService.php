<?php

namespace App\Services\GilbertToCB;
use App\Models\ConnectionApplication;
use http\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Exception;

class UpdateApplicationFromGilbertService
{

    private \Illuminate\Database\Eloquent\Builder|ConnectionApplication|Model $application;

    public function __construct($applicationID)
    {
        $this->application = ConnectionApplication::where('id',$applicationID)->firstOrFail();
    }

    Public function getPropertyData(): array
    {
        return [
            'nmi' => $this->application->nmi,
            'mirn' => $this->application->mirn
        ];

    }
    /**
     * @throws \Exception
     */
    public function call()
    {
        $url = config('bot.root_url') .'/api/gilbert-application/'.$this->application->id;
        $response = Http::put($url , $this->getPropertyData());
        if($response->ok()){
            return $this->sendSuccessResponse('success');
        }
        else {
            \Log::error(json_encode($response->body()));
            throw new \Exception('Fail');
        }
    }


    protected function sendSuccessResponse(string $message, int $statusCode = 200, $payload = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'success' => 1,
//            'data' => $payload
        ], $statusCode);
    }



    public static function shouldUpdateChatbotNmiMirn(ConnectionApplication $app)
    {
        if(!$app->chatbot_id) {
            return false;
        }

        return $app->isDirty('nmi') || $app->isDirty('mirn');
    }




}

