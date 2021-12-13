<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class APILog extends Model
{
    use HasFactory;

    const API_HB_CREATE_CONTACT = 'hs_create_contact';
    const API_HB_UPDATE_CONTACT = 'hs_update_contact';
    const API_SUMO_SUBMIT_LEAD = 'sumo_submit_lead';
    const API_FAST_CONNECT_WATER_SUBMIT = 'fast_connect_water_submit';
    const API_SALES_API_SUBMIT = 'sales_api_submit';
    const API_SALES_API_GET_STATUS = 'sales_api_get_status';

    protected $table = 'api_logs';

    protected $guarded = ['id'];


    public static function setLoggerQuery(string $url, string $type, bool $extend = true): string
    {
        $type = "&hd_logger_type=$type";
        $key = Str::uuid()->toString();
        return $extend
            ? $url ."&hd_logger_key=" . $key . $type
            : $url . "?hd_logger_key=" . $key . $type;
    }
}
