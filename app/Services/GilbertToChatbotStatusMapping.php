<?php

namespace App\Services;


use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class GilbertToChatbotStatusMapping
{

    public const CB_STATUS_REJECTED='rejected';
    public const CB_STATUS_SENT='sent';
    public const CB_AC_MANUAL_PROCESSING='ac_manual_precessing';
    public const CB_MANUAL_PROCESSING='manual_precessing';
    public const CB_STATUS_ACCEPTED='accepted';
    public const CB_STATUS_PENDING='pending';
    public const CB_STATUS_SUBMITTED='submitted';
    public const CB_STATUS_COMPLETE='complete';
    public const NEW_GILBERT='new_gilbert';


    const GB_TO_CB_MAPPING = [
        ConnectionService::STATUS_SUBMITTED => 'submitted',
        ConnectionService::STATUS_ACCEPTED =>'accepted',
        ConnectionService::STATUS_REJECTED => 'rejected',
        ConnectionService::AC_MANUAL_PROCESSING => 'ac_manual_precessing',
//        ConnectionService::STATUS_SUBMITTED => 'sent',
//        ConnectionService::AC_MANUAL_PROCESSING => 'ac_manual_precessing'
//        ConnectionService::AC_MANUAL_PROCESSING => 'ac_manual_precessing'
    ];

    const CB_TO_GB_MAPPING = [
        self::CB_STATUS_SUBMITTED => ConnectionService::STATUS_SUBMITTED,
        self::CB_STATUS_ACCEPTED => ConnectionService::STATUS_ACCEPTED,
        self::CB_STATUS_REJECTED => ConnectionService::STATUS_REJECTED,
        self::CB_AC_MANUAL_PROCESSING => ConnectionService::AC_MANUAL_PROCESSING,
        self::CB_STATUS_SENT => ConnectionService::STATUS_SUBMITTED,
        self::CB_MANUAL_PROCESSING => ConnectionService::AC_MANUAL_PROCESSING,
        self::CB_STATUS_PENDING => ConnectionService::STATUS_SUBMITTED,
        self::CB_STATUS_COMPLETE => ConnectionService::STATUS_ACCEPTED
    ];

}
