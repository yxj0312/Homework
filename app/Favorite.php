<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    /**
     * Favorite is assoicated with reply.
     *
     * If we use 'tinker' to get App\Favorite::latest()->first()->favorited
     *
     * We can see: we loaded this Reply as well as any relvant date for favorite.
     *
     * @return void
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
