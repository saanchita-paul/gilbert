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

    public function addException($exception, $data = [])
    {
        if (str_contains(get_class($exception), 'GuzzleHttp')) {
            if ($exception->hasResponse()) {
                $message = Psr7\Message::toString($exception->getResponse());
                $data['uri'] = $exception->getRequest()->getUri();
                $this->exceptionData[] = [
                    'message' => $message,
                    'data' => json_encode($data)
                ];
            }
            else {
                $this->exceptionData[] = [
                    'message' => $exception->getMessage(),
                    'data' => json_encode($data)
                ];
            }
        }
        else {
            $this->exceptionData[] = [
                'message' => $exception->getMessage(),
                'data' => json_encode($data)
            ];
        }
    }

    public function run ()
    {
        if (!$this->hasExceptions()) {
            \Log::error($this->fileName . ' throw exception attempted with no exceptions');
            return;
        };

        Mail::to(config('mri.to_mail_address'))->queue(new NotifyFetchFailMail($this->fileName, $this->exceptionData));
        dump('Error in '. $this->fileName);
        foreach($this->exceptionData as $e){
            dump($e['message']);
        }

        \Log::error($this->fileName . ' FAILED (Refer Context)', $this->exceptionData);
        
        throw new \Exception($this->fileName . ' FAILED (Refer Logs)');
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