<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamAnswer implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public array $answer) {}

    public function broadcastOn(): Channel
    {
        return new Channel('stream-signal');
    }
}