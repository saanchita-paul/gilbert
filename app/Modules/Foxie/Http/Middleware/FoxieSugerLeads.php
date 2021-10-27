<?php

namespace Foxie\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class FoxieSugerLeads
{
    /**
     * Handle an incoming request.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $registeredKey = "QGe1FqGkvacdZu1TiUlKabbAtaaUUOEYl6oFLJCQx93wNYiHvuZrnRuPk5pe";
            $apiKeyInHeader = trim( $request->header('x-api-key') );
            
            if($registeredKey != $apiKeyInHeader) throw new Exception("API key is not matched", 1);
            else return $next($request);
        } catch (\Throwable $th) {
            return response(["status" => "failed", "message" =>  "Your x-api-key is not matched"], 401);
        }
    }
}
