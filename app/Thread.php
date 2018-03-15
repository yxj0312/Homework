<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected  $guarded = [];

    // ##############################################################
    // Relations
    // ##############################################################

    public function replies()
    {
       return $this->hasMany(Reply::class);
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
