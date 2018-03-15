<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters 
{
    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
         $this->request = $request;
    }
    /**
     * Accept the query builder
     */
    public function apply($builder)
    {
        // We apply our filters to the builder
        if (!$username = $this->request->by) {
            return $builder;
        }
        
        $user = User::where('name', $username)->firstOrFail();
        
        return $builder->where('user_id', $user->id);
    }
}