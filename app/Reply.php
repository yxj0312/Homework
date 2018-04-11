<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
		});

		static::deleted(function ($reply) {
			$reply->thread->decrement('replies_count');
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

	// ##############################################################
    // Methods
    // ##############################################################

	public function path()
	{
		// We do need a id to hook to, so that we can direct go the favorited reply.
		return $this->thread->path() . "#reply-{$this->id}";
	}

	public function wasJustPublished()
	{
		// gt : greater than
		return $this->created_at->gt(Carbon::now()->subMinute());
	}
}
