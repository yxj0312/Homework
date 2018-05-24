<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ThreadReceiveNewReply
{
    use SerializesModels;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
