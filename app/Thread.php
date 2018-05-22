<?php

namespace App;

// use App\Notifications\ThreadWasUpdated;
use Laravel\Scout\Searchable;
use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceiveNewReply;
use Illuminate\Database\Eloquent\Model;

// U can wrapper Facades before path to use a real time facade.
// use Facades\App\Reputation;

class Thread extends Model
{
    // use RecordsActivity, RecordsVisits;
    use RecordsActivity, Searchable;
    

    protected $guarded = [];


    // ##############################################################
    // Global Query Scopes
    // ##############################################################

    protected $with = ['creator', 'channel'];

    protected $appends =['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

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
            Reputation::reduce($thread->creator, Reputation::THREAD_WAS_PUBLISHED);
        });

        static::created(function ($thread){
            $thread->update(['slug' => $thread->title]);
            // Then it is going to hit setSlugAttribute method.

            // $thread->creator->increment('reputation', 10);
            // $thread->creator->increment('reputation', Reputation::THREAD_WAS_PUBLISHED);
            Reputation::award($thread->creator, Reputation::THREAD_WAS_PUBLISHED);         
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
        return "/threads/{$this->channel->slug}/{$this->slug}";
        // return '/threads/' . $this->channel->slug . '/' .  $this->id;
    }

    /**
     * A thread can have a best reply.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bestReply()
    {
        return $this->hasOne(Reply::class, 'thread_id');
    }

    public function addReply($reply)
    {
        /* if ($this->locked) {
            // can return response here like controller.
            // Then go Handler.php of Exception
            throw new \Exception('Thread is locked');
        } */

        // $reply = $this->replies()->create($reply);

        // $this->increment('replies_count');
        
        // return $reply;

        $reply = $this->replies()->create($reply);

        /* Prepare notifications for all subscribers.

        Refactor als collection below */
        // foreach ($this->subscriptions as $subsciption) {
        //     if($subsciption->user_id != $reply->user_id) {
        //         $subsciption->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }
        
        event(new ThreadReceiveNewReply($reply));

        /**
         * This ist the simplest approach of refactor
         * Better than make an event at this moment. Ep 46
         * Refactor to aboved event at Ep 57.
         */
        // $this->notifySubscribers($reply);

        /** Only refator codes to event, if u feel codes getting bigger */
        // event(new ThreadHasNewReply($this, $reply));

        //Move this to event listener.
        // $this->subscriptions
        // /* Refactor 2. */
        //     ->where('user_id', '!=', $reply->user_id)
        // //     ->filter(function ($sub) use ($reply) {
        // //         return $sub->user_id != $reply->user_id;
        // // })
        // /* Refactor 1. */
        // // ->each(function ($sub) use($reply) {
        // //     $sub->user->notify(new ThreadWasUpdated($this, $reply));
        // // });
        // ->each->notify($reply);

        return $reply;
    }

    /**
     * Remove the following two, cause there is a lock method in laravel QueryBuild,
     * 
     * which is overwritten  the here.
     *
     * @return void
     */
    // public function lock()
    // {
    //     $this->update(['locked' => true]);
    // }

    // public function unlock()
    // {
    //     $this->update(['locked' => false]);
    // }

    /**
     * Refactor to listener at Ep 57
     *
     */
    // public function notifySubscribers($reply)
    // {
    //     $this->subscriptions
    //         ->where('user_id', '!=', $reply->user_id)
    //         ->each
    //         ->notify($reply);
    // }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ? : auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ? : auth()->id())
            ->delete();
    }

    // public function hasUpdatesFor($user = null)
    public function hasUpdatesFor($user)
    {
        // $user = $user ?: auth()->user();

        // Look in the cache for the proper key (maybe return a carbon instance).

        // compare that carbon instance (reflect to the last time user visited page) with
        // the $thread->updated_at

        // users.50.visits.1, unique key, that will be equal to a timestamp.
        // $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    // public function visits()
    // {
    //     // If u hat anything, return; otherwise, default to 0.
    //     /* return Redis::get($this->visitsCacheKey()) ?? 0; */
    //     return new Visits($this);
    // }

    /**
     * Refactored: move to setSlugAttribute
     *
     * @param [type] $slug
     * @param integer $count
     * @return void
     */
    public function incrementSlug($slug, $count = 2)
    {
        $original = $slug; 
        // $count = 2;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        return $slug;

        // i.e. give u help-me-8 (maxium for the title)
        // but after 10, it will not work.
        /* static::whereTitle($this->title)->max('slug'); */
        // Instead:
        /* static::whereTitle($this->title)->max('id'); */
        // Or:
        /* static::whereTitle($this->title)->latest('id')->first(); */#
        
        // Redesign
        /* $max = static::whereTitle($this->title)->latest('id')->value('slug'); */
        
        /* if(substr($max, -1, 1)) */
        // $max is actually an array, and we fetch the last char (-1)
        // i.e 'laracasts'[-1] = s
        // Redesign
        /* if(is_numeric($max[-1])) { */
            // look for a digit(\d)
            // one or more(+)
            // thats needs to occur at the end of the string($)
            /* return preg_replace_callback('/(\d+)$/', function ($matches) { */
                // if u find one, then trigger the callback function here
                /* return $matches[1] + 1; */
                // we are searching through $max
            /* }, $max); */
        /* }

        return "{$slug}-2"; */
    }

    public function MarkBestReply(Reply $reply)
    {
        if ($this->hasBestReply()) {
            Reputation::reduce($this->bestReply->owner, Reputation::BEST_REPLY_AWARDED);
        }

        $this->update(['best_reply_id' => $reply->id]);

        // $reply->owner->increment('reputation', 50);
        Reputation::award($reply->owner, Reputation::BEST_REPLY_AWARDED);

        // $this->best_reply_id = $reply->id; 

        // $this->save();
    }

    /**
     * Overwrite toSearchableArray
     * Use php artisan scout:import "App\Thread" after overwrite
     * 
     * @return void
     */
    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }

    /**
     * Determine if the thread has a current best reply.
     *
     * @return bool
     */
    public function hasBestReply()
    {
        return !is_null($this->best_reply_id);
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
            ->where('user_id', auth()->id())
            ->exists();
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);
        $original = $slug;
        // $count = 2;

        // 1. n number of queries.
        // 2. Help Me => help-me
        // 3. If help-me is deleted, and someone else create a new one with the same name
        // 4. This could be a total different component with same link
        // 5. To solve this, we can use primary key instead of count

        // Another way: /threads/channel/89/the-slug-of-the-thread

        while (static::whereSlug($slug)->exists()) {
            // $slug = "{$original}-" . $count++;
            // Maybe u can md5 the id
            $slug = "{$original}-" . $this->id;
        }
        
        // if(static::whereSlug($slug = str_slug($value))->exists()) {
        //     $slug = $this->incrementSlug($slug);
        // }

        $this->attributes['slug'] = $slug;
    }
}
