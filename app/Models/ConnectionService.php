<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\ConnectionService
 *
 * @property int $id
 * @property int $connection_application_id
 * @property string|null $service_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ConnectionApplication $connectionApplication
 * @method static \Database\Factories\ConnectionServiceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereConnectionApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConnectionService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConnectionService extends Model
{
    use HasFactory;


    const TYPE_ELECTRICITY = 'power';
    const TYPE_GAS = 'gas';
    const TYPE_WATER = 'water';
    const TYPE_INTERNET = 'internet';

    const STATUS_UNASSIGNED = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_ESCALATED = 3;
    const STATUS_SUBMITTED = 4;
    const STATUS_ACCEPTED = 5;
    const STATUS_REJECTED = 6;
    const STATUS_EA_PROCESSINF = 7; //todo: rename this constant to STATUS_NOT_SUBMITTED
    const STATUS_ENERGY_SUBMIT = 12;
    const STATUS_CLOSED = 8;
    const STATUS_CANT_CONNECT = 9;
    const STATUS_NEEDS_MORE_INFO = 10;
    const AC_MANUAL_PROCESSING = 11;
    const STATUS_FAILED = 13;

    // Do not use these statuese
    const WATER_STATUS_IN_PROGRESS = 7; // initial status
    const WATER_STATUS_NEED_INFO = 10;
    const WATER_STATUS_SUBMITTED = 4;
    const WATER_STATUS_CONNECTED = 5;
    const WATER_STATUS_CANT_CONNECT = 9;

    const PROVIDER_SUMO = 'sumo';
    const PROVIDER_EA = 'ea';
    const PROVIDER_ORIGIN = 'origin';
    const PROVIDER_POWER_SHOP = 'powershop';


    const STATUS_MAPPING = [
        self::STATUS_UNASSIGNED => 'unassigned',
        self::STATUS_ASSIGNED=>'assigned',
        self::STATUS_ESCALATED => 'escalated',
        self::STATUS_SUBMITTED => 'submitted',
        self::STATUS_ACCEPTED =>'accepted',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_EA_PROCESSINF => 'processing',
        self::STATUS_ENERGY_SUBMIT => 'processing',
        self::STATUS_CLOSED => 'closed',
        self::STATUS_CANT_CONNECT => 'can\'t_connect',
        self::STATUS_NEEDS_MORE_INFO => 'need_more_info',
        self::AC_MANUAL_PROCESSING => 'ac_manual_precessing',
        self::STATUS_FAILED => 'failed',
    ];

    public const ENERGY_AUSTRALIA_BASIC_PLAN = 'Basic - Home';
    public const ENERGY_AUSTRALIA_NO_FRILLS = 'No Frills (Home)';
    public const ENERGY_AUSTRALIA_TOTAL_PLAN = 'Total Plan (Home)';
    public const ENERGY_AUSTRALIA_TOTAL_PLUS_12_PLAN = 'Total Plan Plus 12 (Home)';

    public const PLAN_ORIGIN_HOME_ASSIST = 'origin_home_assist';
    public const PLAN_ORIGIN_ADVANTAGE_VARIABLE = 'origin_advantage_variable';
    public const PLAN_ORIGIN_HOME_SUPPORT = 'origin_home_support';
    public const PLAN_ORIGIN_SUPPLY = 'origin_supply';
    public const PLAN_ORIGIN_BASIC = 'origin_basic';

    public const ENERGY_PLAN_MAPPER = [
        'basic_plan' => self::ENERGY_AUSTRALIA_BASIC_PLAN,
        'no_frills' => self::ENERGY_AUSTRALIA_NO_FRILLS,
        'total_plan' => self::ENERGY_AUSTRALIA_TOTAL_PLAN,
        'total_plan_plus_12' => self::ENERGY_AUSTRALIA_TOTAL_PLUS_12_PLAN,
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connection_application_id',
        'service_type',
        'status',
        'reason',
        'connection_date',
        'provider_name',
        'plan_type',
        'lead_reference',
    ];

    /**
     * @return BelongsTo
     */
    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }

    public function reasons()
    {
        return $this->hasMany(RejectionReason::class, 'connection_service_id');
    }

    public function allApplicationMetricsCount(array $matrixReq, User $user)
    {

        info('matrics print' , ['matrixReq' => $matrixReq]);
        $service = DB::table('connection_services AS CS');
        info('user',[$user->profile_type]);
        if(!empty($matrixReq['agency_id'])){
            $service->where('CA.agency_id', $matrixReq['agency_id']);
        } else {
            if($user->profile_type === AgentProfile::class) {
            $profileId = $user->profile->id;
            $office_id = $user->profile->office_id;
                $service->where('office_id', '=', $office_id );
            }

        }

        $copyService = $service;
        $nopayCount = 0;

            $result = $service->leftJoin('connection_applications AS CA', 'CA.id', '=',
            'CS.connection_application_id')
            ->select(
                DB::raw("SUM(CASE
            WHEN CA.status = 4 OR CA.status = 2 THEN 1 ELSE 0 END) AS applications"),

                DB::raw("SUM(CASE
            WHEN CA.status = 6 THEN 1 ELSE 0 END) AS nonpayable"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'power' and CS.status=5 THEN 1 ELSE 0 END) AS power"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'gas' and CS.status=5 THEN 1 ELSE 0 END) AS gas"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'internet' and CS.status = 5 THEN 1 ELSE 0 END) AS internet"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'water' and CS.status = 5 THEN 1 ELSE 0 END) AS water")
            )
            ->get();

        if(!empty($matrixReq['agency_id'])){
            $appCount = DB::table('connection_applications')
//                ->whereIn('status',[
//                ConnectionApplication::STATUS_ACCEPTED,
//                ConnectionApplication::STATUS_EA_PROCESSINF,
//                ConnectionApplication::STATUS_REJECTED,
//            ])
                ->where('agency_id', $matrixReq['agency_id'])
                ->count();

            $nopayCount = DB::table('connection_applications')
                ->where('agency_id', $matrixReq['agency_id'])
                ->whereIn('status',[
                ConnectionApplication::STATUS_REJECTED,
                ConnectionApplication::STATUS_CLOSED,
            ]) ->count();


        }
        else {
            if($user->profile_type === AgentProfile::class) {
                $agency_id = $user->profile->agency_id;
                $office_id = $user->profile->office_id;
                $profile_id = $user->profile->id;
                $appCount = DB::table('connection_applications')
                    ->where('office_id','=', $office_id)
                    ->count();

                $nopayCount = DB::table('connection_applications')->whereIn('status',[
                    ConnectionApplication::STATUS_REJECTED,
                    ConnectionApplication::STATUS_CLOSED,
                ])
                    ->where('created_by','=', $profile_id)
                    ->where('agency_id', '=', $agency_id )
                    ->count();

            }
            else {
                $appCount = DB::table('connection_applications')
//                    ->whereIn('status',[
//                    ConnectionApplication::STATUS_ACCEPTED,
//                    ConnectionApplication::STATUS_EA_PROCESSINF,
//                    ConnectionApplication::STATUS_REJECTED,
//                ])
                    ->count();

                $nopayCount = DB::table('connection_applications')->whereIn('status',[
                    ConnectionApplication::STATUS_REJECTED,
                    ConnectionApplication::STATUS_CLOSED,
                ]) ->count();

            }

        }

//        $nopayCount = DB::table('connection_applications')->where('status','=',
//            ConnectionApplication::STATUS_CLOSED)->count();



        $result = $result->toArray()[0];
        $result->applications = $appCount;
        $result->nonpayable = $nopayCount;
        return $result;
    }
}
