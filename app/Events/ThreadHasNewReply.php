<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;


class ThreadHasNewReply
{
    use SerializesModels;
    /**
     * For event listeners, anything u want to accessable should be public.
     *
     * @var [type]
     */
    public $thread;

    public $reply;

    /**
     * Create a new event instance.
     *
     * @param [\App\Thread]  $thread
     * @param [\App\reply] $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

}
