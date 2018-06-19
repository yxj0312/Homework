<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];

    /* Global Scopes */
    protected $with = ['owner', 'favorites'];

    /**
     * Whenever you cast an array oder cast JSON,
     * Any custom attributes that you want to append that.
     *
     * @var array
     */
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];
    // protected $appends = ['favoritesCount', 'isFavorited'];

    /**
     * Benefit of this approach, compare to increment in thread.php:
     * If use create('App\Reply'), it will automatically add replies_count to related thread.
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');

            // $reply->owner->increment('reputation', 2);
            // Reputation::gain($reply->owner, Reputation::REPLY_POSTED);
            $reply->owner->gainReputation('reply_posted');
        });

        static::deleting(function ($reply) {
            if ($reply->isBest()) {
                $reply->owner->loseReputation('best_reply_awarded');
            }
        });

        static::deleted(function ($reply) {
            // We can do it in the DB level.
            /* if ($reply->isBest()) {
                $reply->thread->update(['best_reply_id' => null]);
            } */

            $reply->thread->decrement('replies_count');

            // Reputation::lose($reply->owner, Reputation::REPLY_POSTED);
            $reply->owner->loseReputation('reply_posted');
        });
    }

    // ##############################################################
    // Relations
    // ##############################################################
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the related title for the reply.
     */
    public function title()
    {
        return $this->thread->title;
    }

    // ##############################################################
    // Methods
    // ##############################################################

    public function path()
    {
        // We do need a id to hook to, so that we can direct go the favorited reply.
        return $this->thread->path()."#reply-{$this->id}";
    }

    public function wasJustPublished()
    {
        // gt : greater than
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function setBodyAttribute($body)
    {
        // We look for a reg exp of some sorts('/@[^\s]+/') to find a username,
        // We wrap that with an anchor tag ('<a href="#"></a>')
        // Then, lastly, we are looking to the $body
        // /@([^\s]+) means:
        // @: after @ symbol; [^]: Anything; \s that is not space; +: find one or more
        // (): wrap everything we matched, excluding the @ symbol,
        // otherwise u will get @JaneDoe ($0); $1: JaneDoe

        /* $this->attributes['body'] = preg_replace('/@([^\s\.]+)/', '<a href="/profiles/$1">$0</a>', $body); */

        // Hey @JaneDoe.
        // Hey @Jane.Doe!
        // Hey @Jane-Doe?
        // @Jane Doe, help me.
        // /@[\w]+/ means:
        // \w: Give me word character; + one or more;
        // \- : And a dash; \s: And a space
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }
}
