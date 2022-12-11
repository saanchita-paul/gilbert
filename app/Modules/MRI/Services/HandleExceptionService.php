<?php

namespace MRI\Services;

use MRI\Mail\NotifyFetchFailMail;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Psr7;

class HandleExceptionService
{
    private string $fileName;
    private array $exceptionData;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->exceptionData = [];
    }

    public function addException($exception, $extraData)
    {
        $this->exceptionData[] = [
            'exception' => $exception,
            'extraData' => $extraData
        ];
    }

    private function formatException()
    {
        $exceptionsFormatted = [];
        foreach ($this->exceptionData as $ed) {
            $exception = $ed['exception'];
            $extraData = $ed['extraData'];

            $exceptionMessage = $exception->getMessage();
            $extraData['trace'] = $exception->getTraceAsString();
            if (str_contains(get_class($exception), 'GuzzleHttp') && $exception->hasResponse()) {
                $exceptionMessage = Psr7\Message::toString($exception->getResponse());
                $extraData['uri'] = $exception->getRequest()->getUri();
            }

            $exceptionsFormatted[] = [
                'message' => $exceptionMessage,
                'data' => json_encode($extraData),
            ];
        }
        return $exceptionsFormatted;
    }

    public function run()
    {
        if (!$this->hasExceptions()) {
            \Log::error($this->fileName . ' throw exception attempted with no exceptions');
            return;
        };

        $formattedExceptions = $this->formatException();

        $emails = explode(',', config('support_email.tech'));
        foreach ($emails as $recipient) {
            if (!empty($recipient)) {
                Mail::to($recipient)->queue(new NotifyFetchFailMail($this->fileName, $formattedExceptions));
            }
        }

        \Log::error($this->fileName . ' FAILED (Refer Context)', $this->exceptionData);

        $firstException = $this->exceptionData[0]['exception'];
        throw $firstException;
        // throw new \Exception($this->fileName . ' FAILED (Refer Logs)');
    }

    public function hasExceptions()
    {
        return count($this->exceptionData) > 0;
    }

    public function getExceptionData()
    {
        return $this->exceptionData;
    }
}
