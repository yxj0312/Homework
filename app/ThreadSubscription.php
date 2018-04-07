<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    // ##############################################################
    // Relations
    // ##############################################################
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    // ##############################################################
    // Methods
    // ##############################################################
    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));        
    }
}
