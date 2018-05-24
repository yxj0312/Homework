<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;

    protected $filters = [];

    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Accept the query builder.
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
            // 7th Refactor
        /* foreach ($this->filters as $filter) { */
            //8th
            /* if (!$this->hasFilter($filter))  return;  */
            // 6th Refactor
            /* if (method_exists($this,$filter) && $this->request->has($filter)) { */
            /* $this->$filter($this->request->$filter); */
        }
        // 5th Refactor
        /* if ($this->request->has('by')) {
            $this->by($this->request->by);
        } */

        return $this->builder;
        //4th Refactor
        // We apply our filters to the builder
        /* if (!$username = $this->request->by) {
            return $builder;
        } */

        //3rd Refactor
        /* return $this->by($username); */

        //2nd Refactor
        /* return $this->by($builder, $username); */

        //1st Refactor
        /* $user = User::where('name', $username)->firstOrFail();
        //All Thread where 'user_id' is equal to this one.
        return $builder->where('user_id', $user->id); */
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }

    /* protected function hasFilter($filter)
    {
        return method_exists($this, $filter) && $this->request->has($filter);
    } */
}
