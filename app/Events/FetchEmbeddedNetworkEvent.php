<?php

namespace App\Events;

use App\Models\ConnectionApplication;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FetchEmbeddedNetworkEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $applicationId;
    private $message;
    private $application;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($applicationId, $message = null)
    {
        $this->applicationId = $applicationId;
        $this->message = $message ?? 'Embedded network fetched successfully!';
        $this->application = ConnectionApplication::find($this->applicationId);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fetchEmbeddedNetwork.' . $this->applicationId);
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
            'embedded_nmi' => $this->application->embedded_nmi,
            'loading_address_info' => $this->application->loading_address_info,
        ];
    }
}
