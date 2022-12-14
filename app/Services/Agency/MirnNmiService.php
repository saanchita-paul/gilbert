<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Services\FastConnectService;

class MirnNmiService
{
    public static function fetchMirnNmi($application_id)
    {
        $application = ConnectionApplication::find($application_id);

        $result = [
            'mirn' => $application->mirn ?? null,
            'nmi' => $application->nmi ?? null,
        ];

        if ($application && (!$application->mirn || !$application->nmi)) {
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->searchAddress([], true, $application->id);
        }

        return $result;
    }

    public static function fetchIsEmbedded($application_id)
    {
        $application = ConnectionApplication::find($application_id);

        $result = [
            'is_embedded' => $application->is_embedded ?? null,
        ];

        if ($application && $application->nmi && is_null($application->is_embedded)) {
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->fetchEmbeddedNetwork("", true, $application->id);
        }

        return $result;
    }
}
