<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //Git rid of Mass assignment exception of test
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed($user, $take = 50)
    {
        /* The date include the date and time, in which are unique
        So we pass a closure here to group date. */
        // return $user->activity()
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }
}
