<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];


    // ##############################################################
    // Global Query Scopes
    // ##############################################################

    protected $with = ['creator', 'channel'];

    protected $appends =['isSubscribedTo'];

    //This can be used anywhere.
    // Compared to above, with this we can use i.e. App\Thread::withoutGlobalScopes()->first()
    protected static function boot()
    {
        parent::boot();

        /* Add a new table column: replies_count to thread table */
        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });


        // static::addGlobalScope('creator', function ($builder) {
        //     $builder->with('creator');
        // });

        // Option 2nd, to prevent delete thread if replies exist. 
        static::deleting(function ($thread) {
            // $thread->replies()->delete();
            // So that, deleting activity will be fired for every single reply.
            $thread->replies->each->delete();
            /* $thread->replies->each(function ($reply) {
                $reply->delete();
            }); */
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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
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
        // $reply = $this->replies()->create($reply);

        // $this->increment('replies_count');
        
        // return $reply; 

        return $this->replies()->create($reply);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ? : auth()->id()
        ]);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->delete();
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

    // ##############################################################
    // Accessor
    // ##############################################################
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id',auth()->id())
            ->exists();
    }
}
