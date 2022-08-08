<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;

class AgentStatusProgressMapper
{
    /**
     * @param $applicationStatus
     * @return array
     */
    public static function getAgentApplicationStatus($applicationStatus): array
    {
        $links = [];
        switch ($applicationStatus) {
            case ConnectionApplication::STATUS_UNASSIGNED: // 1
                $links[] = self::getNewProgressStatus();
                break;
            case ConnectionApplication::STATUS_ASSIGNED: // 2
                array_push($links, self::getNewProgressStatus(), self::getContactingProgressStatus());
                break;
            case ConnectionApplication::STATUS_SUBMITTED: // 4
                array_push($links, self::getNewProgressStatus(), self::getContactingProgressStatus(), self::getConfirmedProgressStatus());
                break;
            case ConnectionApplication::STATUS_CLOSED: // 8
                array_push($links, self::getNewProgressStatus(), self::getClosedProgressStatus());
                break;
            default:
                $links[] = [];
                break;
        }

        return $links;
    }

    /**
     * @return array
     */
    private static function getNewProgressStatus()
    {
        return [
            'step_name' => 'New',
            'description' => 'We have received the application and will be in touch with the customer very soon.',
            'active' => true
        ];
    }

    /**
     * @return array
     */
    private static function getContactingProgressStatus()
    {
        return [
            'step_name' => 'Contacting',
            'description' => 'We are attempting to contact the customer to confirm their connections.',
            'active' => true
        ];
    }

    /**
     * @return array
     */
    private static function getConfirmedProgressStatus()
    {
        return [
            'step_name' => 'Confirmed',
            'description' => 'We have spoken to the customer and confirmed their connections.',
            'active' => true
        ];
    }


    /**
     * Getting close status progress data
     *
     * @return array|null
     */
    private static function getClosedProgressStatus()
    {
        return [
            'step_name' => 'Closed',
            'description' => 'The customer decided not to go ahead or we could not get in touch with them.',
            'active' => true
        ];
    }

}
