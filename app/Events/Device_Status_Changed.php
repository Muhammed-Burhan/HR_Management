<?php

namespace App\Events;

use App\Models\Device;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Device_Status_Changed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $device;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user,Device $device)
    {
        $this->user=$user;
        $this->device=$device;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
