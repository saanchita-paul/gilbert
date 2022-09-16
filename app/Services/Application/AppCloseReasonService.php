<?php

namespace App\Services\Application;

use App\Models\AppCloseReason;

class AppCloseReasonService
{
    /**
     * get all closing reason lists
     */
    public function getAppClosingReasonList()
    {
        $reasons = AppCloseReason::query()->where('value','!=' , 'Others')->orderBy('value')->get();
        $others = AppCloseReason::query()->where('value', 'Others')->get();
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
        $appCloseReason = AppCloseReason::findOrFail($id);
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
}
