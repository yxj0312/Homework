<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceiveNewReply;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceiveNewReply  $event
     * @return void
     */
    public function handle(ThreadReceiveNewReply $event)
    {
        // Inspect the body of the reply for username mentions
        // We need some regular expensions: goto https://regexr.com/
        // Compare rege with body, and save to $matches
        // preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);

        /* $mentionedUsers = $event->reply->mentionedUsers(); */

        // Give me all of the user u have, just as long as the name calling exist in this array
        User::whereIn('name', $event->reply->mentionedUsers())->get()
             ->each(function ($user) use ($event) {
                 $user->notify(new YouWereMentioned($event->reply));
             });

        // Foreach mentionedUsers, map of them and return name of user.
       /*  collect($event->reply->mentionedUsers())
            ->map(function ($name) {
                return  User::whereName($name)->first();
            })
            // Or it couldn't have found any user with that name and return null
            // call filter (as method with no argument, it will strip all no values)
            ->filter()
            // Foreach one of those, notify the user.
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            }); */

        // And then for each mentioned user, notify them
        /* foreach ($mentionedUsers as $name) {
            if ($user = User::whereName($name)->first()) {
                $user->notify(new YouWereMentioned($event->reply));
            }
        } */
    }
}
