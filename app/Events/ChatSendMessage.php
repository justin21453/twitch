<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChatSendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $message;
    public $setting;

    /**
     * Create a new event instance.
     */
    public function __construct($name, $message, $setting)
    {
        Log::info("__construct");
        $this->name = $name;
        $this->message = $message;
        $this->setting = $setting;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        Log::info("broadcastOn");
        return new Channel('chat');

        // return [
        //     new PrivateChannel('chat'),
        // ];
    }

    public function broadcastWith()
    {
        return [
            'name' => $this->name,
            'message' => $this->message,
            'setting' => $this->setting,
        ];
    }

    public function broadcastAs()
    {
        return 'ChatSendMessage';
    }
}
