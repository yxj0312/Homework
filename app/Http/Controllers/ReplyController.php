<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Inspections\Spam;
use Auth;

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
  public function store($channelId, Thread $thread)
  {
    // $this->validate(request(), [
    //   'body' => 'required'
    // ]);
    // $spam->detect(request('body'));
    try {
      $this->validateReply();

      $reply = $thread->addReply([
        'body' => request('body'),
        'user_id' => auth()->id()
      ]);
    } catch (\Exception $e) {
      return response(
        'Sorry, your reply could not be saved at this time.',
        422
      );
    }

    /* if (request()->expectsJson()) {
      return $reply->load('owner');
    }

    return back()->with('flash', 'Your reply has been left.'); */

    return $reply->load('owner');
  }

  public function update(Reply $reply)
  {
    $this->authorize('update', $reply);

    try {

      $this->validateReply();

      // $reply->update(['body' => request('body')]);
      $reply->update(request(['body']));
    } catch (\Exception $e) {
      return response(
        'Sorry, your reply could not be saved at this time.',
        422
      );
    }


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
  protected function validateReply()
  {
    $this->validate(request(), ['body' => 'required']);
    resolve(Spam::class)->detect(request('body'));
  }

}
