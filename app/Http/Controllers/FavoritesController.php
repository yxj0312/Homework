<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
// use App\Reputation;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Reply $reply)
    {
        $reply->favorite();

        // Reputation::gain($reply->owner, Reputation::REPLY_FAVORITED);
        $reply->owner->gainReputation('reply_favorited');

        // return back();
        /* $reply->favorites()->create(['user_id' => auth()->id()]); */

        /* Favorite::create([
            'user_id' => auth()->id(),
            'favorited_id' => $reply->id,
            'favorited_type' => get_class($reply)
        ]); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        // Reputation::lose($reply->owner, Reputation::REPLY_FAVORITED);
        $reply->owner->loseReputation('reply_favorited');
    }
}
