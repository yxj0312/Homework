<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;
use Carbon\Carbon;
use App\Rules\SpamFree;
use App\Rules\Recaptcha;
use App\Inspections\Spam;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use Illuminate\Validation\Rule;

class ThreadController extends Controller
{
    /**
     * Create a new ThreadController instance.
     */
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
    // public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    public function index(Channel $channel, ThreadFilters $filters)
    {
        // $threads = Thread::filter($filters)->get();

        $threads = $this->getThreads($channel, $filters);

        /* if($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        } */

        /*
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

        // $trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', [
            'threads' => $threads,
            // 'trending' => $trending->get()
            'channel' => $channel
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('threads.create');
        return view('threads.create', [
            // 'channels' => Channel::orderBy('name', 'asc')->get()#
            'channels' => Channel::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Spam $spam, Recaptcha $recaptcha)
    {
        /* if(! auth()->user()->confirmed) {
            return redirect('/threads')->with('flash','You must first confirm your email address.');
        } */

        request()->validate([
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
            // 'channel_id' => 'required|exists:channels,id',
            'channel_id' => [
                'required',
                Rule::exists('channels', 'id')->where(function ($query) {
                    $query->where('archived', false);
                })
            ],

            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        // $spam->detect(request('body'));

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
            // No longer need to, happened automatically by the model event
            /* 'slug' => str_slug(request('title')) */
        ]);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        // return redirect(route('threads.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]))
        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        // return $thread->load('replies');
        // return Thread::withCount('replies')->find($thread->id);
        // return $thread;
        // $replies = $thread->replies()->paginate(20);
        // return view('threads.show', compact('channel', 'thread', 'replies'));

        // Code duplicated, can be stored as const/method in user class or thread class.
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        // Throw all things into redis, in this way, u will never need to query in the DB.
        $trending->push($thread);

        /* Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ])); */

        // $thread->recordVisit();

        // $thread->visits()->record();

        $thread->increment('visits');

        /*  // Record that the user visited this page
        $key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);

        // Record a timestamp, when they did so. Make it equal to a current time.
        cache()->forever($key, Carbon::now()); */

        // Another aproach: create a visit table, and fetch from i.e. $user->visits()->create()

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
        // $threads = Thread::latest()->filter($filters);
        // $threads = Thread::latest('pinned')->latest()->filter($filters);
        $threads = Thread::latest('pinned')->latest()->with('channel')->filter($filters);

        //Queries reduction: eager load with 'with'
        // $threads = Thread::with('channel')->latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads->paginate(config('homework.pagination.perPage'));
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
    public function update($channel, Thread $thread)
    {
        // Refactored: move to LockedThreadsController
        /* if (request()->has('locked')) {
            // authorization
            if (! auth()->user()->isAdmin()) {
                return response('', 403);
            }

            $thread->lock();
        } */

        // This is good, but a little bit confusing.
        // You need to know tap and HigherOrderTapProxy
        /* return tap($thread)->update(request()->validate([
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
        ])); */
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
        ]));

        // wrap them above will return a boolean of updated or not.
        return $thread;

        // $thread->update(request(['title', 'body']));
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
