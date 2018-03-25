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
      $this->validate(request(),[
        'body' => 'required'
      ]);
      $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
      ]);

      return back()->with('flash', 'Your reply has been left.');
    }

    public function update(Reply $reply)
    {
      $this->authorize('update', $reply);
      // $reply->update(['body' => request('body')]);
      $reply->update(request(['body']));
      
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
