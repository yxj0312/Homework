<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * php artisan make:policy ReplyPolicy --model=Reply.
     *
     * Don't forget go to 'AuthServiceProvider' and register this.
     */

    /**
     * Determine whether the user can view the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function view(User $user, Reply $reply)
    {
        //
    }

    /**
     * Determine whether the user can create replies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (! $lastReply = $user->fresh()->lastReply) {
            return true;
        }

        return ! $lastReply->wasJustPublished();
    }

    /**
     * Determine whether the user can update the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        return $reply->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the reply.
     *
     * @param  \App\User  $user
     * @param  \App\Reply  $reply
     * @return mixed
     */
    public function delete(User $user, Reply $reply)
    {
        //
    }
}
