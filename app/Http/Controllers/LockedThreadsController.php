<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        // Use a middleware, we can get rid of this entirely
        // if (!auth()->user()->isAdmin()) {
        //     return response('You do not have permission to lock this thread.', 403);
        // }

        // $thread->lock();
        $thread->update(['locked' => true]);
    }

    public function destroy(Thread $thread)
    {
        // $thread->unlock();
        $thread->update(['locked' => false]);
    }
}
