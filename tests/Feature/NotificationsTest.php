<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Then each time a new reply is left
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);
        // A notificaion should be prepared for the user.
        // $this->assertCount(1, auth()->user()->notifications);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications()
    {
        // $thread = create('App\Thread')->subscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'Some reply here'
        // ]);

        create(DatabaseNotification::class);

        // $user = auth()->user();

        // $response = $this->getJson("/profiles/{ $user->name }/notifications")->json();

        $this->assertCount(
            1,
            $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json()
        );
    }

    /** @test */
    function a_user_can_mark_a_notification_as_read()
    {
        // $thread = create('App\Thread')->subscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'Some reply here'
        // ]);

        create(DatabaseNotification::class);

        // $user = auth()->user();

        // $this->assertCount(1, $user->unreadNotifications);

        // $notificationId = $user->unreadNotifications->first()->id;

        // $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        // $this->assertCount(0, $user->fresh()->unreadNotifications);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $this->delete("/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
