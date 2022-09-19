<?php

namespace App\Services\Application;

use App\Models\AppCloseReason;

class AppCloseReasonService
{
    /**
     * get all closing reason lists
     */
    public function getAppClosingReasonList($activeOnly=false, $ordered=false)
    {
        $reasons = AppCloseReason::query()->where('value','!=' , 'Others');
        $others = AppCloseReason::query()->where('value', 'Others');

        if ($activeOnly){
            $reasons = $reasons->where(function ($query){
                $query->where('is_inactive', false)
                        ->orWhereNull('is_inactive');
            });
            $others = $others->where(function ($query){
                $query->where('is_inactive', false)
                        ->orWhereNull('is_inactive');
            });
        }

        if ($ordered){
            $reasons = $reasons->orderBy('value');
        }

        $reasons = $reasons->get();
        $others = $others->get();
        return $reasons->concat($others);
    }
    /**
     * create closing reason
     */
    public function createAppClosingReason(array $data)
    {
        return AppCloseReason::create($data);
    }
    /**
     * show closing reason
     */
    public function showAppClosingReason(int $id)
    {
        return AppCloseReason::findOrFail($id);
    }
    /**
     * update closing reason
     */
    public function updateAppClosingReason(array $data, int $id)
    {
        $appCloseReason = AppCloseReason::withTrashed()->findOrFail($id);
        $appCloseReason->update($data);
        return $appCloseReason;
    }

    /**
     * delete closing reason
     */
    public function deleteAppClosingReason(int $id)
    {
        return AppCloseReason::findOrFail($id)->delete();
    }

    /**
     * enable closing reason
     * 
     */
    public function enableAppClosingReason(int $id)
    {
        return AppCloseReason::findOrFail($id)->enable();
    }

    /**
     * enable closing reason
     * 
     */
    public function disableAppClosingReason(int $id)
    {
        return AppCloseReason::findOrFail($id)->disable();
    }
}
