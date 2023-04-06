<?php

namespace MRI\Services;

use App\Models\MriLog;
use Psr\Http\Message\ResponseInterface;

class LogService
{
    private MriLog $log;

    public function create(string $type, string $url, array $data = [])
    {
        $data['type'] = $type;
        $data['url'] = $url;

        $this->log = MriLog::create($data);
    }

    public function update(ResponseInterface $response)
    {
        if (empty($this->log)) {
            return;
        }

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();
        $contents = $body->getContents();
        $data = [
            'status_code' => $statusCode,
            'response_body' => $contents
        ];
        $body->rewind();
        $this->log->update($data);
    }

    public function getLogId()
    {
        return $this->log->id ?? null;
    }
}
