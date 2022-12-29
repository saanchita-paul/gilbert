<?php

namespace App\Events;

use App\Models\ConnectionApplication;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FetchMirnNmiEvent implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    private $applicationId;
    private $message;
    private $application;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($applicationId, $message=null)
    {
        $this->applicationId = $applicationId;
        $this->message = $message ?? 'MIRN and NMI fetched successfully!';
        $this->application = ConnectionApplication::find($this->applicationId);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fetchMirnNmi.' . $this->applicationId);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'connection_application_id' => $this->applicationId,
            'message' => $this->message,
            'mirn' => $this->application->mirn,
            'nmi' => $this->application->nmi,
            'loading_address_info' => $this->application->loading_address_info,
        ];
    }
}
