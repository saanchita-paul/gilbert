<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Services\Address\EmbeddedNetworkService;
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

    public static function fetchMirnNmiWithoutUnit($application_id)
    {
        $application = ConnectionApplication::find($application_id);

        $result = [
            'mirn' => null,
            'nmi' => null,
        ];

        if ($application) {
            $svcUtilities = new EmbeddedNetworkService();
            $result = $svcUtilities->authenticate()->searchAddressWithoutUnit([], true, $application->id);
        }

        return $result;
    }

    public static function fetchIsEmbedded($nmi = null, $applicationFlag = false, $applicationId = null)
    {
        if ($applicationFlag) {
            $mirnNmiResult = self::fetchMirnNmiWithoutUnit($applicationId);
            $nmi = $mirnNmiResult['nmi'];
        }

        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchEmbeddedNetwork($nmi, $applicationFlag, $applicationId);
    }
}
