<?php

namespace App\Listeners;

use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        // Inspect the body of the reply for username mentions
        // We need some regular expensions: goto https://regexr.com/
        // Compare rege with body, and save to $matches
        // preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);

        /* $mentionedUsers = $event->reply->mentionedUsers(); */

        // Give me all of the user u have, just as long as the name calling exist in this array
        /* User::whereIn('name', $event->reply->mentionedUsers())->get()
             ->each(function ($user) use ($event) {
                 $user->notify(new YouWereMentioned($event->reply));
             }); */

        tap($event->subject(), function ($subject) {
            User::whereIn('name', $this->mentionedUsers($subject))
                ->get()->each->notify(new YouWereMentioned($subject));
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

    /**
     * Fetch all mentioned users within the reply's body.
     *
     * @return array
     */
    public function mentionedUsers($body)
    {
        preg_match_all('/@([\w\-]+)/', $body, $matches);

        return $matches[1];
    }
}
