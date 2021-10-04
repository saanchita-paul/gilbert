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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'connection_application_id',
        'service_type'
    ];

    /**
     * @return BelongsTo
     */
    public function connectionApplication()
    {
        return $this->belongsTo(ConnectionApplication::class);
    }

    public function allApplicationMetricsCount(array $matrixReq, $user)
    {

        $service = DB::table('connection_services AS CS');
        if(!empty($matrixReq['agency_id'])){
            $service->where('CA.agency_id', $matrixReq['agency_id']);
        }else{
            if($user->profile_type === AgentProfile::class) {
            $officeId = $user->profile->office_id;
                $service->where('CA.office_id', $officeId);
            }
        }

        $copyService = $service;

          $result = $service->leftJoin('connection_applications AS CA', 'CA.id', '=', 'CS.connection_application_id')
            ->select(
                DB::raw("SUM(CASE
            WHEN CA.status = 4 OR CA.status = 2 THEN 1 ELSE 0 END) AS applications"),

                DB::raw("SUM(CASE
            WHEN CA.status = 6 THEN 1 ELSE 0 END) AS nonpayable"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'power' AND CA.status = 5 THEN 1 ELSE 0 END) AS power"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'gas' AND CA.status = 5 THEN 1 ELSE 0 END) AS gas"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'internet' AND CA.status = 5 THEN 1 ELSE 0 END) AS internet"),

                DB::raw("SUM(CASE
            WHEN CS.service_type = 'water' AND CA.status = 5 THEN 1 ELSE 0 END) AS water")
            )
            ->get();

          $appCount = DB::table('connection_applications')->whereIn('status',[
              ConnectionApplication::STATUS_ACCEPTED,
              ConnectionApplication::STATUS_SUBMITTED,
              ConnectionApplication::STATUS_ASSIGNED,
              ])->count();
        $nopayCount = DB::table('connection_applications')->where('status','=',ConnectionApplication::STATUS_REJECTED)->count();



        $result = $result->toArray()[0];
        $result->applications = $appCount;
        $result->nonpayable = $nopayCount;
        return $result;
    }
}
