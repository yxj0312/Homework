<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    /**
     * Filter the query by a given username.
     * Extrac0t from above
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        //All Thread where 'user_id' is equal to this one.
        return $this->builder->where('user_id', $user->id);
    }
}