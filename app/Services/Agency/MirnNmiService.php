<?php

namespace App\Services\Agency;

use App\Services\FastConnectService;

class MirnNmiService
{
    public function fetchMirnNmi($application)
    {
        $result = [
            'mirn' => $application->mirn,
            'nmi' => $application->nmi
        ];
        if ($application->office->is_chatbot_office && (!$application->mirn || !$application->nmi)) {
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->searchAddress([], true, $application->id);
        }

        return $result;
    }

    public function fetchIsEmbedded($application)
    {
        $result = [
            'is_embedded' => $application->is_embedded,
        ];
        if (!$application->is_embedded) {
            $svcUtilities = new FastConnectService();
            $result = $svcUtilities->authenticate()->fetchEmbeddedNetwork("", true, $application->id);
        }

        return $result;
    }
}
