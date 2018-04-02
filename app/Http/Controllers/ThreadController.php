<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Channel;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth')->only(['create','store']);
        $this->middleware('auth')->except(['index', 'show']);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        // $threads = Thread::filter($filters)->get();

        $threads = $this->getThreads($channel, $filters);

        /* if($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        } */

        /**
         * When we called the filter method on Thread.php, 
         * that will ask our thread filter to apply(See scopeFilter) itself to the query
         * in ThreadFilters, when we accept our request, and call the apply method
         * we will find the user or failed, and apply the user-id to the query 
         */
        /* $threads = $threads->filter($filters)->get();  */   
        // if ($username = request('by')) { 
        //     $user = \App\User::where('name', $username)->firstOrFail();
        //     $threads->where('user_id', $user->id);
        // }

        // $threads = $threads->get();


        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect(route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]))
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        // return $thread->load('replies');
        // return Thread::withCount('replies')->find($thread->id);
        // return $thread;
        // $replies = $thread->replies()->paginate(20);
        // return view('threads.show', compact('channel', 'thread', 'replies'));
        return view('threads.show', compact('thread'));
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        
        //Queries reduction: eager load with 'with'
        // $threads = Thread::with('channel')->latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // Option 2nd, to prevent delete thread if replies exist.
        // Option 1 see create_replies_table
        /* $thread->replies()->delete(); */

        $this->authorize('update', $thread);
        
        // if ($thread->user_id != auth()->id()) {
        //     abort(403, 'You do not have permission to do this.');
            /* if (request()->wantsJson()) {
                return response(['status' => 'Permission Denied'], 403);
            }

            return redirect('/login'); */
        // }

        // Option 3rd, overwrite the delete() method.
        $thread->delete();

        // Option 4nd: Model convention, see Thread.php
        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }
}
