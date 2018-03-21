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
}
