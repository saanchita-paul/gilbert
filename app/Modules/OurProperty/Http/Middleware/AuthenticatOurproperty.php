<?php

namespace OurProperty\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class AuthenticatOurproperty
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
            $registeredKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE5ZjFmZmJlMjY1NWM3ZjFkODljMDE3ZWI0NWI5ZGE2ODZjZDE3ZmY1NmY0OTA5NGUwZmVhYmYwNTQ1ZDQ4YjU1MmFjNjM1ZGY1ZjYwM2QxIn0.eyJhdWQiOiIxIiwianRpIjoiYTlmMWZmYmUyNjU1YzdmMWQ4OWMwMTdlYjQ1YjlkYTY4NmNkMTdmZjU2ZjQ5MDk0ZTBmZWFiZjA1NDVkNDhiNTUyYWM2MzVkZjVmNjAzZDEiLCJpYXQiOjE2MzUzMDk0ODAsIm5iZiI6MTYzNTMwOTQ4MCwiZXhwIjoxNjY2ODQ1NDgwLCJzdWIiOiIyNyIsInNjb3BlcyI6W119.LOo49acFNxSC9gQXnJEmZRWk74Hf_8nEZemZI-5zDOH7BgM2CFXcQ12ii5NbZmHNjt4lPTh2vYDO-m-YEYvd4SMTLQOc1mSBi8AkjbCNpnNiSPKnlBLjRzRE8mXWAxpevvXmcCL_KDrslTk348jHbW1L1SeE8J0X6uosd4L3pef0skfMdG0QMD9XoGSIfD01bjKIxcRPbOpmpE7Tqnuz9aKpF4UiN5Gd0VSBa3_TRkMgJm7OHZCcHg33FGJHYrcFAcSAPq5rJ_3pNCoM0emNXKsDxua4Yb-esKBfdHKTBzknnu8yEnZaJ61iJFHCm5IqhRmAXm8jG0t0hD0oKhLrPNs0HpmQlSNQeUly8OgQxhj2PGejxAvpgmTNJ4eC_UcXQhIJdHpyhwVB2CdvNApSGWQQxagGeqeE54ZtudxU0qILClgXZeCSo40KeASa_1T7-TJduJbZflUEteSk9m_KmmNccrV-99gcY4n8LDGVwu6ta8ZrfxeeQv6H_hP1E6ghFAJjvnJSJVWqCshxHkVltSWlqj1ArLEq-UXcCFqV96FnRUAy_o_B9fy_LdlraQ_IrMCLlojBjdI5vdSf7TZLjp11aTR8nKGEVuP6rPUyMpOWds7n6F9M9dw86NFYvT1gWhZwVrbNZ1euAOz4hyS4g98BvE1NHrJ-0_GjeGTNISc";
            $apiKeyInHeader = trim( $request->header('x-api-key') );

            if($registeredKey != $apiKeyInHeader) throw new Exception("API key is not matched", 1);
            else return $next($request);
        } catch (\Throwable $th) {
            return response(["status" => "failed", "message" =>  "Your x-api-key is not matched"], 401);
        }
    }
}
