<?php

namespace App;

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
	protected $appends = ['favoritesCount', 'isFavorited'];
	
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

	// ##############################################################
    // Methods
    // ##############################################################

	public function path()
	{
		// We do need a id to hook to, so that we can direct go the favorited reply.
		return $this->thread->path() . "#reply-{$this->id}";
	}
}
