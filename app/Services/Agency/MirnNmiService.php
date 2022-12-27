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
            'mirn' => null,
            'nmi' => null,
        ];

        if ($application) {
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

        if ($application && $application->unit_number) {
            $svcUtilities = new EmbeddedNetworkService();
            $result = $svcUtilities->authenticate()->searchAddressWithoutUnit([], true, $application->id);
        }

        if ($application && !$application->unit_number) {
            $result = [
                'mirn' => $application->mirn,
                'nmi' => $application->nmi,
            ];
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

    public static function fetchIsEmbeddedWithNmi($nmi, $applicationFlag = false, $applicationId = null)
    {
        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchEmbeddedNetwork($nmi, $applicationFlag, $applicationId);
    }
}
