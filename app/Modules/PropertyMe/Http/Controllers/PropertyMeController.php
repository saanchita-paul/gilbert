<?php

namespace PropertyMe\Http\Controllers;

class PropertyMeController
{
    private function isConnected(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['pm_connected' => \Cache::get('pm_connected')]);
    }
}
