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
    const API_HB_GET_CONTACT_BY_EMAIL = 'hs_get_contact_by_email';
    const API_SUMO_SUBMIT_LEAD = 'sumo_submit_lead';
    const API_FAST_CONNECT_WATER_SUBMIT = 'fast_connect_water_submit';
    const API_TSA_INSERT_DATA = 'tsa_send_application_data';
    const API_SALES_API_SUBMIT = 'sales_api_submit';
    const API_SALES_API_GET_STATUS = 'sales_api_get_status';
    const API_ORIGIN_GET_PRODUCT_INFO = 'origin_get_product_info';
    const API_ORIGIN_VALIDATE_ADDRESS = 'origin_validate_address';
    const API_ORIGIN_CHECK_FUEL = 'origin_check_fuel';
    const API_ORIGIN_CHECK_STATUS = 'origin_check_status';
    const API_ORIGIN_SUBMIT_CUSTOMER_MOVE_IN = 'origin_submit_customer_move_in';
    const API_ORIGIN_SUBMIT_CANCEL = 'origin_submit_cancel';


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
