<?php

namespace ExternalLead\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ExternalLead\Services\SaveRawData;
use App\Models\ExternalSource;
use ExternalLead\Models\ExternalLeadApiLog;

class LogRequestResponse
{
    public ExternalLeadApiLog $dump;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->has('username')) {
            $requestData = $request->all();
            $username = $request->input('username');
            $selectedSource = ExternalSource::where('email', $username)->first();
            $dump = SaveRawData::dump($selectedSource->id, $requestData);
            $requestData['dump_id'] = $dump->id;
            $request->replace($requestData);
        }
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if ($request->has('dump_id')) {
            $dump = ExternalLeadApiLog::find($request->input('dump_id'));
            $dump->api_response_body = $response->getContent();
            $dump->status_code = $response->getStatusCode();
            $dump->save();
        }
    }
}
