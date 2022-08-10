<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;

class AgentStatusProgressMapper
{
    private int $assignTo;
    private int $applicationStatus = 8;
    private array $services;
    private array $links = [];

//    public function __construct($application)
//    {
//
//    }


    /**
     * @return array
     */
    public function getAgentApplicationStatus(): array
    {
        switch ($this->applicationStatus) {
            case ConnectionApplication::STATUS_UNASSIGNED: // 1
                $this->links[] = $this->getNewProgressStatus();
                break;
            case ConnectionApplication::STATUS_ASSIGNED: // 2
                array_push(
                    $this->links,
                    $this->getNewProgressStatus(),
                    $this->getContactingProgressStatus()
                );
                break;
            case ConnectionApplication::STATUS_SUBMITTED: // 4
            case ConnectionApplication::STATUS_ACCEPTED: // 5
            case ConnectionApplication::STATUS_EA_PROCESSINF: // 7
                array_push(
                    $this->links,
                    $this->getNewProgressStatus(),
                    $this->getContactingProgressStatus(),
                    $this->getConfirmedProgressStatus()
                );
                break;
            case ConnectionApplication::STATUS_CLOSED: // 8
                array_push(
                    $this->links,
                    $this->getNewProgressStatus(),
                    $this->getClosedProgressStatus()
                );
                break;
            default:
                $this->links = [];
                break;
        }

        return $this->links;
    }

    private function handleClosedStatus()
    {

    }

    /**
     * Getting new status progress data
     *
     * @return array
     */
    private function getNewProgressStatus()
    {
        return [
            'step_name' => 'New',
            'description' => 'We have received the application and will be in touch with the customer very soon.',
            'active' => true
        ];
    }

    /**
     * Getting contacting status progress data
     *
     * @return array
     */
    private function getContactingProgressStatus()
    {
        return [
            'step_name' => 'Contacting',
            'description' => 'We are attempting to contact the customer to confirm their connections.',
            'active' => true
        ];
    }

    /**
     * Getting confirmed status progress data
     *
     * @return array
     */
    private function getConfirmedProgressStatus()
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
     * @return array
     */
    private function getClosedProgressStatus()
    {
        return [
            'step_name' => 'Closed',
            'description' => 'The customer decided not to go ahead or we couldn\'t get in touch with them.',
            'active' => true
        ];
    }

    /**
     * @return string
     */
    public function getApplicationStatus(): string
    {
        return match ($this->applicationStatus) {
            ConnectionApplication::STATUS_UNASSIGNED => 'New',
            ConnectionApplication::STATUS_ASSIGNED, ConnectionApplication::STATUS_ESCALATED => 'Contacting',
            ConnectionApplication::STATUS_SUBMITTED, ConnectionApplication::STATUS_ACCEPTED, ConnectionApplication::STATUS_EA_PROCESSINF => 'Confirmed',
            ConnectionApplication::STATUS_CLOSED => 'Closed',
            default => 'Unknown',
        };
    }

}
