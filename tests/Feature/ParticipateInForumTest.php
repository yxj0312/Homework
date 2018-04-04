<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function unauthenticated_users_may_not_add_replies()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');

		$this->post('threads/some-channel/1/replies', []);
	}


	/** @test */
	public function an_authenticated_user_may_participate_in_forum_threads()
	{
    	// $user = factory('App\User')->create();
		$this->be($user = factory('App\User')->create());

		$thread = factory('App\Thread')->create();
		$reply = factory('App\Reply')->make();

		$this->post($thread->path() . '/replies', $reply->toArray());

    	// $this->get($thread->path())
    	// 	->assertSee($reply->body); 

		$this->assertDatabaseHas('replies', ['body' => $reply->body]); 
		/* Use fresh() because after wenn create $thread, we have also updated it. 
		No fresh() will not give us the fresh data */
		$this->assertEquals(1, $thread->fresh()->replies_count);
	}

	/** @test */
	public function a_reply_requires_a_body()
	{
		$this->withExceptionHandling()->signIn();

		$thread = create('App\Thread');

		$reply = make('App\Reply', ['body' => null]);

		$this->post($thread->path() . '/replies', $reply->toArray())
			->assertSessionHasErrors('body');
	}

	/** @test */
	function unauthenticated_users_can_not_delete_replies()
	{
		$this->withExceptionHandling();

		$reply = create('App\Reply');

		$this->delete("/replies/{$reply->id}")
			->assertRedirect('/login');

		$this->signIn()
			->delete("/replies/{$reply->id}")
			->assertStatus(403);// vorbidden class
	}

	/** @test */
	function authorized_users_can_delete_replies()
	{
		$this->signIn();

		$reply = create('App\Reply', ['user_id' => auth()->id()]);

		$this->delete("/replies/{$reply->id}")
			->assertStatus(302);

		$this->assertDatabaseMissing('replies', ['id' => $reply->id]);

		$this->assertEquals(0, $reply->thread->fresh()->replies_count);
	}

	/** @test */
	function unauthenticated_users_can_not_update_replies()
	{
		$this->withExceptionHandling();

		$reply = create('App\Reply');

		$this->patch("/replies/{$reply->id}")
			->assertRedirect('/login');

		$this->signIn()
			->patch("/replies/{$reply->id}")
			->assertStatus(403);// vorbidden class
	}

	/** @test */
	function authorized_users_can_update_replies()
	{
		$this->signIn();

		$reply = create('App\Reply', ['user_id' => auth()->id()]);

		$updateReply = 'You been changed, fool.';

		$this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

		$this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
	}
}
