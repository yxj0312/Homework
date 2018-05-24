<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
    /* public function recordVisit()
    {
        // Redis::incr('visits') -> can't do like this
        // Because for every single thread, it will overwrite this key
        // Instead, we gonna using namespacing
        Redis::incr($this->visitsCacheKey());

        return $this;
    } */

    /* public function visits()
    {
        // If u hat anything, return; otherwise, default to 0.
        // return Redis::get($this->visitsCacheKey()) ?? 0;

        return new Visits($this);
    } */

    /* public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    } */

    /* public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    } */
}
