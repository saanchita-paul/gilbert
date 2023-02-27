<?php

namespace App\Services\Agency;

use App\Events\FetchEmbeddedNetworkEvent;
use App\Events\FetchMirnNmiEvent;
use App\Models\ConnectionApplication;
use App\Services\Address\EmbeddedNetworkService;
use App\Services\FastConnectService;

class MirnNmiService
{
    /**
     * Fetching and saving Application Mirn & NMI
     *
     * @param ConnectionApplication $application
     * @return void
     */
    public static function saveApplicationMirnNmi(ConnectionApplication $application): void
    {
        $svcUtilities = new FastConnectService($application->toArray());
        $result = $svcUtilities->searchAddress();

        $application->fill($result)->save();
    }

    /**
     * Save Application NMI Embedded
     *
     * @param ConnectionApplication $app
     * @return void
     */
    public static function fetchNmiIsEmbedded(ConnectionApplication $app): void
    {
        $app->embedded_nmi = null;
        $nmi = $app->nmi ?? $app->suggested_nmi;

        if ($nmi) {
            $service = new EmbeddedNetworkService();
            $result = $service->isNmiEmbeddedNetwork($nmi);
            $app->embedded_nmi = $result === true ? 1 : null;
        }

        $app->save();
    }


    /**
     * @param $applicationId
     * @return ConnectionApplication
     * @throws \Exception
     */
    public static function dispatchAllService($applicationId): ConnectionApplication
    {
        /** @var ConnectionApplication $application */
        $application = ConnectionApplication::find($applicationId);

        if (!$application) {
            throw new \Exception("No Application found with ID: $applicationId");
        }

        MirnNmiService::saveApplicationMirnNmi($application);

        $application->update(['loading_address_info' => false]);

        event(new FetchMirnNmiEvent($applicationId));

        MirnNmiService::fetchNmiIsEmbedded($application->refresh());

        event(new FetchEmbeddedNetworkEvent($applicationId));

        return $application->refresh();
    }
}
