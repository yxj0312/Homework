<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	use Favoritable, RecordsActivity;

	protected  $guarded = [];

	/* Global Scopes */
	protected $with = ['owner', 'favorites'];
	
	// ##############################################################
    // Relations
    // ##############################################################
	public function owner()
	{
	   return $this->belongsTo(User::class, 'user_id');
	}

	// ##############################################################
    // Methods
    // ##############################################################
}
