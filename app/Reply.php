<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected  $guarded = [];
	
	// ##############################################################
    // Relations
    // ##############################################################
	public function owner()
	{
	   return $this->belongsTo(User::class, 'user_id');
	}

	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favorited');
	}

	// ##############################################################
    // Methods
    // ##############################################################
	public function favorite()
	{
		$attributes = ['user_id' => auth()->id()];

		if (!$this->favorites()->where($attributes)->exists()) {
			//Because we set morphMany above, we just add auth_id here.
			return $this->favorites()->create($attributes);
		}
	}
}
