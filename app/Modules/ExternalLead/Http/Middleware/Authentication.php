<?php

namespace ExternalLead\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Services\AuthService\JwtAuthService;

class Authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            $apiKeyInHeader = trim($request->header('Authorization'));
            $token = explode(" ", $apiKeyInHeader)[1] ??  "";
            $tokenUsername =  JwtAuthService::checkAccessToken($token);
            if ($tokenUsername) {
                $request->request->add(['username' => $tokenUsername]);
                return $next($request);
            } else {
                throw new Exception("API key is not matched", 1);
            }
        } catch (\Throwable $th) {
            return response([ "message" =>  "unauthenticated"], 401);
        }
    }
}
