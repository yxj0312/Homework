<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected  $guarded = [];


    // ##############################################################
    // Global Query Scopes
    // ##############################################################

    protected $with =['creator', 'channel'];

    //This can be used anywhere.
    // Compared to above, with this we can use i.e. App\Thread::withoutGlobalScopes()->first()
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount',function($builder){
            $builder->withCount('replies');
        });


        // static::addGlobalScope('creator', function ($builder) {
        //     $builder->with('creator');
        // });

        // Option 2nd, to prevent delete thread if replies exist.
        static::deleting(function ($thread){
            $thread->replies()->delete();
        });
    }

    // ##############################################################
    // Relations
    // ##############################################################

    public function replies()
    {
        /* Eager load: when we catch the replies for a thread
        as part of that process, I want to include the count of the
        favorites relationship */
       return $this->hasMany(Reply::class);
        /**
         * Anytime I ever fetch a reply, I am gonna need access to the creator.
         * So it will be nice, if we just had a global scope, that said, yep,
         * for every single reply query, I want you to eager the owner.
         */
            // ->withCount('favorites')
            // ->with('owner');
    }

    public function creator()
    {
       return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
       return $this->belongsTo(Channel::class);
    }

    // ##############################################################
    // Methods
    // ##############################################################

    public function path()
    {
      //refactor
      return "/threads/{$this->channel->slug}/{$this->id}";
    	// return '/threads/' . $this->channel->slug . '/' .  $this->id;   
    }

    public function addReply($reply)
    {
       $this->replies()->create($reply); 
    }

    // ##############################################################
    // Query Scopes
    // ##############################################################
    /**
     * We want set this filter to current running query
     * so we also set an query scope
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
