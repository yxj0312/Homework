<?php

namespace Tests\Feature;

use App\Thread;
use App\Mentions;
use Tests\TestCase;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {

			// If it recevie it should call the 'passes' method, it should return true.
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function mentioned_users_in_a_thread_are_notified()
    {
        // Given we have a user, JohnDoe, who is signed in.
        $john = create(\App\User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);
        // And we also have a user, JaneDoe.
        $jane = create(\App\User::class, ['name' => 'JaneDoe']);
        // And JohnDoe create a new thread and mentions @JaneDoe.
        $thread = make(\App\Thread::class, [
            'body' => 'Hey @JaneDoe check this out.'
        ]);

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);
        
        // Then @JaneDoe should receive a notification.
        $this->assertCount(1, $jane->notifications);
        $this->assertEquals(
            "JohnDoe mentioned you in \"{$thread->title}\"",
            $jane->notifications->first()->data['message']
        );
    }

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        // Given I have a user, JohnDoe, who is signed in
        $john = create('App\User', ['name' => 'JohnDoe']);
        
        $this->signIn($john);
        
        // And another user, JaneDoe
        $jane = create('App\User', ['name' => 'JaneDoe']);
        
        // If we have a thread
        $thread = create('App\Thread');

        // And JohnDoe replies and mentions JaneDoe.
        $reply = make('App\Reply', [
            'body' => '@JaneDoe look at this.Also @FrankDoe'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        // Then, JaneDoe should be notified
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);        
        create('App\User', ['name' => 'janedoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);
        
        $this->assertCount(2, $results->json());
    }
}
