<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use Auth;

class ReplyController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function store($channelId, Thread $thread)
  {
    $this->validate(request(), [
      'body' => 'required'
    ]);

    $reply = $thread->addReply([
      'body' => request('body'),
      'user_id' => auth()->id()
    ]);

    if (request()->expectsJson()) {
      return $reply->load('owner');
    }

    return back()->with('flash', 'Your reply has been left.');
  }

  public function update(Reply $reply)
  {
    $this->authorize('update', $reply);

      // $reply->update(['body' => request('body')]);
    $reply->update(request(['body']));

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

}
