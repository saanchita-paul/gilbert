<?php

namespace App\Services\Agency;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;

class AgentStatusProgressMapper
{
    /**
     * @var mixed
     */
    private mixed $assignedTo;
    /**
     * @var mixed
     */
    private mixed $applicationStatus;
    /**
     * @var mixed
     */
    private mixed $services;
    /**
     * @var array
     */
    private array $links = [];

    /**
     * @param array $applicationData
     */
    public function __construct(array $applicationData)
    {
        $this->assignedTo = $applicationData['assignedTo'];
        $this->applicationStatus = $applicationData['applicationStatus'];
        $this->services = $applicationData['applicationServices'];
    }


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
            case ConnectionApplication::STATUS_ESCALATED: // 3
                $this->handleEscalatedApplicationStatus();
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
                $this->handleCloseApplicationStatus();
                break;
            default:
                $this->links = [];
                break;
        }

        return $this->links;
    }

    /**
     * handle application close status
     *
     * @return void
     */
    private function handleCloseApplicationStatus(): void
    {
        if ($this->assignedTo == null) {
            array_push(
                $this->links,
                $this->getNewProgressStatus(),
                $this->getClosedProgressStatus()
            );
        } else if ($this->checkServiceStatusForContacting() && $this->assignedTo != null) {
            array_push(
                $this->links,
                $this->getNewProgressStatus(),
                $this->getContactingProgressStatus(),
                $this->getClosedProgressStatus()
            );
        } else if ($this->checkServiceStatusForConfirmed() && $this->assignedTo != null) {
            array_push(
                $this->links,
                $this->getNewProgressStatus(),
                $this->getContactingProgressStatus(),
                $this->getConfirmedProgressStatus(),
                $this->getClosedProgressStatus()
            );
        } else {
            $this->links = [];
        }
    }

    /**
     * Check application close after contacting
     *
     * @return bool
     */
    private function checkServiceStatusForContacting(): bool
    {
        $electricity = false;
        $gas = false;
        foreach ($this->services as $service){
            if ($service->service_type == 'power' &&
                $service->status == ConnectionService::STATUS_EA_PROCESSINF) {
                $electricity = true;
            }
            if ($service->service_type == 'gas' &&
                $service->status == ConnectionService::STATUS_EA_PROCESSINF) {
                $gas = true;
            }
        }
        return $electricity && $gas;
    }

    /**
     * Check application close after confirmed
     *
     * @return bool
     */
    private function checkServiceStatusForConfirmed(): bool
    {
        $electricityOrGas = false;
        foreach ($this->services as $service) {
            if (($service->service_type == 'power' || $service->service_type == 'gas') &&
                ($service->status == ConnectionService::STATUS_SUBMITTED ||
                    $service->status == ConnectionService::STATUS_ACCEPTED ||
                    $service->status == ConnectionService::STATUS_REJECTED ||
                    $service->status == ConnectionService::STATUS_EA_PROCESSINF)) {
                $electricityOrGas = true;
            }
        }
        return $electricityOrGas;
    }

    /**
     * handle application escalated status
     *
     * @return void
     */
    private function handleEscalatedApplicationStatus(): void
    {
        if ($this->assignedTo == null || ($this->checkServiceStatusForContacting() && $this->assignedTo != null)) {
            array_push(
                $this->links,
                $this->getNewProgressStatus(),
                $this->getContactingProgressStatus()
            );
        } else if ($this->checkServiceStatusForConfirmed()) {
            array_push(
                $this->links,
                $this->getNewProgressStatus(),
                $this->getContactingProgressStatus(),
                $this->getConfirmedProgressStatus()
            );
        } else {
            $this->links = [];
        }
    }

    /**
     * Getting new status progress data
     *
     * @return array
     */
    private function getNewProgressStatus(): array
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
    private function getContactingProgressStatus(): array
    {
        return [
            'step_name' => 'Contacting...',
            'description' => 'We are attempting to contact the customer to confirm their connections.',
            'active' => true
        ];
    }

    /**
     * Getting confirmed status progress data
     *
     * @return array
     */
    private function getConfirmedProgressStatus(): array
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
    private function getClosedProgressStatus(): array
    {
        return [
            'step_name' => 'Closed',
            'description' => 'The customer decided not to go ahead or we could not get in touch with them.',
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
            ConnectionApplication::STATUS_ASSIGNED => 'Contacting...',
            ConnectionApplication::STATUS_ESCALATED => $this->getEscalatedStatus(),
            ConnectionApplication::STATUS_SUBMITTED,
            ConnectionApplication::STATUS_ACCEPTED,
            ConnectionApplication::STATUS_REJECTED,
            ConnectionApplication::STATUS_EA_PROCESSINF => $this->checkStatusForConfirmed(),
            ConnectionApplication::STATUS_CLOSED => 'Closed',
            default => '',
        };
    }

    /**
     * @return string
     */
    private function getEscalatedStatus(): string
    {
        foreach ($this->services as $service) {
            if ($service->service_type &&
                ($service->status == ConnectionService::STATUS_SUBMITTED ||
                    $service->status == ConnectionService::STATUS_ACCEPTED ||
                    $service->status == ConnectionService::STATUS_ENERGY_SUBMIT)) {
                return 'Confirmed';
            }
        }

        return 'Contacting...';
    }

    /**
     * Check application status for confirmed
     *
     * @return string
     */
    private function checkStatusForConfirmed(): string
    {
        foreach ($this->services as $service) {
            if ($service->service_type &&
                ($service->status == ConnectionService::STATUS_SUBMITTED ||
                    $service->status == ConnectionService::STATUS_ACCEPTED ||
                    $service->status == ConnectionService::STATUS_REJECTED ||
                    $service->status == ConnectionService::AC_MANUAL_PROCESSING ||
                    $service->status == ConnectionService::STATUS_EA_PROCESSINF)) {
                return 'Confirmed';
            } else {
                return '';
            }
        }
        return '';
    }

}
