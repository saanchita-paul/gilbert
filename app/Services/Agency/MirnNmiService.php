<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Notifications\FetchEmbeddedNetworkNotification;
use App\Notifications\FetchMirnNmiNotification;
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

    public static function fetchNmiIsEmbedded($nmi = null, $applicationFlag = false, $applicationId = null)
    {
        if ($applicationFlag) {
            $mirnNmiResult = self::fetchMirnNmiWithoutUnit($applicationId);
            $nmi = $mirnNmiResult['nmi'];
        }

        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchNmiEmbeddedNetwork($nmi, $applicationFlag, $applicationId);
    }

    public static function fetchNmiIsEmbeddedWithNmi($nmi, $applicationFlag = false, $applicationId = null)
    {
        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchNmiEmbeddedNetwork($nmi, $applicationFlag, $applicationId);
    }

    public static function fetchMirnIsEmbedded($mirn = null, $applicationFlag = false, $applicationId = null)
    {
        if ($applicationFlag) {
            $mirnNmiResult = self::fetchMirnNmiWithoutUnit($applicationId);
            $nmi = $mirnNmiResult['mirn'];
        }

        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchMirnEmbeddedNetwork($mirn, $applicationFlag, $applicationId);
    }

    public static function fetchMirnIsEmbeddedWithNmi($nmi, $applicationFlag = false, $applicationId = null)
    {
        $svcUtilities = new EmbeddedNetworkService();
        return $svcUtilities->authenticate()->fetchMirnEmbeddedNetwork($nmi, $applicationFlag, $applicationId);
    }

    public static function dispatchAllService($applicationId)
    {
        $application = ConnectionApplication::find($applicationId);
        MirnNmiService::fetchMirnNmi($application->id);
        $application->notify(new FetchMirnNmiNotification($application->id));
        MirnNmiService::fetchNmiIsEmbedded(null, true, $application->id);
        $application->notify(new FetchEmbeddedNetworkNotification($application->id));
        $application->update(['loading_address_info' => false]);
        $application->refresh();

        return $application;
    }
}
