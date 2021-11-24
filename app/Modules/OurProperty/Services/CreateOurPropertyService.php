<?php


namespace OurProperty\Services;


use App\Models\ConnectionApplication;
use Exception;
use OurProperty\Models\OurProperty;

class CreateOurPropertyService
{
    /**
     * credetial email
     * 
     * @var string
    */
    private string $email = "lenin@hood.ai";
    /**
     * credetial password
     * 
     * @var string
    */
    private string $password = "Hu435567";
    
    /**
     * server generated access token
     * 
     * @var string
    */
    private string $access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE5ZjFmZmJlMjY1NWM3ZjFkODljMDE3ZWI0NWI5ZGE2ODZjZDE3ZmY1NmY0OTA5NGUwZmVhYmYwNTQ1ZDQ4YjU1MmFjNjM1ZGY1ZjYwM2QxIn0.eyJhdWQiOiIxIiwianRpIjoiYTlmMWZmYmUyNjU1YzdmMWQ4OWMwMTdlYjQ1YjlkYTY4NmNkMTdmZjU2ZjQ5MDk0ZTBmZWFiZjA1NDVkNDhiNTUyYWM2MzVkZjVmNjAzZDEiLCJpYXQiOjE2MzUzMDk0ODAsIm5iZiI6MTYzNTMwOTQ4MCwiZXhwIjoxNjY2ODQ1NDgwLCJzdWIiOiIyNyIsInNjb3BlcyI6W119.LOo49acFNxSC9gQXnJEmZRWk74Hf_8nEZemZI-5zDOH7BgM2CFXcQ12ii5NbZmHNjt4lPTh2vYDO-m-YEYvd4SMTLQOc1mSBi8AkjbCNpnNiSPKnlBLjRzRE8mXWAxpevvXmcCL_KDrslTk348jHbW1L1SeE8J0X6uosd4L3pef0skfMdG0QMD9XoGSIfD01bjKIxcRPbOpmpE7Tqnuz9aKpF4UiN5Gd0VSBa3_TRkMgJm7OHZCcHg33FGJHYrcFAcSAPq5rJ_3pNCoM0emNXKsDxua4Yb-esKBfdHKTBzknnu8yEnZaJ61iJFHCm5IqhRmAXm8jG0t0hD0oKhLrPNs0HpmQlSNQeUly8OgQxhj2PGejxAvpgmTNJ4eC_UcXQhIJdHpyhwVB2CdvNApSGWQQxagGeqeE54ZtudxU0qILClgXZeCSo40KeASa_1T7-TJduJbZflUEteSk9m_KmmNccrV-99gcY4n8LDGVwu6ta8ZrfxeeQv6H_hP1E6ghFAJjvnJSJVWqCshxHkVltSWlqj1ArLEq-UXcCFqV96FnRUAy_o_B9fy_LdlraQ_IrMCLlojBjdI5vdSf7TZLjp11aTR8nKGEVuP6rPUyMpOWds7n6F9M9dw86NFYvT1gWhZwVrbNZ1euAOz4hyS4g98BvE1NHrJ-0_GjeGTNISc";
    /**
     * server generated token_type
     * 
     * @var string
    */
    private string $token_type = "Bearer";
    /**
     * server generated expiry date_type
     * 
     * @var string
    */
    private string $expires_at = "2022-10-27 04:38:00";

    /**
     * Generate token.
     *
     * @param  array  $leadInfo
     * @return array
     * @throws Exception
     */
    public function generateAccessToken(array $credentials) : array|Exception{
        if( $this->email == $credentials['email'] && $this->password == $credentials['password']  ){
            return [
                "access_token" => $this->getAccessToken(),
                "token_type" => $this->token_type,
                "expires_at" => $this->expires_at,
            ];
        }else{
            throw new Exception("Value must be 1 or below");
        }
    }

    /**
     * Modify/Create access token.
     *
     * @param  void
     * @return string
     */
    private function getAccessToken() : string {
        return $this->access_token;
    }

    public function create($requestData) {
        return OurProperty::create($requestData);
    }

    public function createConnectionApp() {
        $conn = ConnectionApplication::create([]);
    }
}
