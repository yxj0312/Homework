<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\User;
use App\Inspections\Spam;
use Auth;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    // public function store($channelId, Thread $thread, Spam $spam)
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        // $this->validate(request(), [
        //   'body' => 'required'
        // ]);
        // $spam->detect(request('body'));

        // if (Gate::denies('create', new Reply)) {
        //   return response(
        //     'You are posting too frequently. Please take a break. :)',
        //     422
        //   );
        // }

        // try {
        // $this->authorize('create', new Reply);
        /*  $this->validateReply();
         This is for laravel 5.4
         $this->validate(request(), ['body' => ['required', new SpamFree()]]);
         This is for 5.5 */
        /**Move to CreatePostRequest */
        // request()->validate(['body' => ['required', new SpamFree()]]);

        // return $form->persist($thread);

      if ($thread->locked) {
        return response('Thread is locked', 422);
      }

        // try { 
      return $thread->addReply([
        'body' => request('body'),
        'user_id' => auth()->id()
      ])->load('owner');
            // Could also see Handler.php from Exception
        // } catch(\Exception $e) {
        //     return response('Locked', 422);
        // }

        // } catch (\Exception $e) {
        //   return response(
        //     'Sorry, your reply could not be saved at this time.',
        //     422
        //   );
        // }

        /* if (request()->expectsJson()) {
          return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.'); */

        // $reply = $thread->addReply([
        //   'body' => request('body'),
        //   'user_id' => auth()->id()
        // ])
        // return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validate(request(), ['body' => ['required', new SpamFree()]]);
        $reply->update(['body' => request('body')]);

        /* try {
          request()->validate(['body' => ['required', new SpamFree()]]);

          $reply->update(request(['body']));
        } catch (\Exception $e) {
          return response(
            'Sorry, your reply could not be saved at this time.',
            422
          );
        } */


      /* Refactoring from Visual Studio Code for PHP Developers: Ep 16 - PHP Full Workflow Review
      Feature of Laravel 5.5 */
      // $validated = request()->validate(['body' => 'required']);
      // $reply->update($validated);
      // $reply->update(request()->validate(['body' => 'required']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        //Refactor: Set a policy for that
        /* if ($reply->user_id != auth()->id()) {
          return response([], 403);
        } */

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    /**
     * Validate the incoming reply.
     */
  // protected function validateReply()
  // {
  //   $this->validate(request(), ['body' => ['required', new SpamFree()]]);
  //   // resolve(Spam::class)->detect(request('body'));
  // }
}
