<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function guest_may_not_create_threads()
	{

		$this->withExceptionHandling();

		$this->get('threads/create')
			->assertRedirect('login');

	  	$this->post('/threads')
	   		->assertRedirect('login');
 

		 /*$this->expectException('Illuminate\Auth\AuthenticationException');

		 // $thread = factory('App\Thread')->make();

		 $thread = make('App\Thread');

		 $this->post('/threads', $thread->toArray());*/
	}

	/**
	 * According to the EP8: Beside recomment the throw in Handler.php.
	 * You should also phpunit --filter=guests_cannot_see_the_create_thread_page
	 * Or it could be thrown by the aboved test.
	 * 
	 */
	/** @test */
	// public function guests_cannot_see_the_create_thread_page()
	// {
	//    $this->withExceptionHandling()
	//    			->get('/threads/create')
	//    			 ->assertRedirect('login');
	// }

	/** @test */
	public function an_authenticated_user_can_create_new_forum_threads()
	{
		 // $this->actingAs(factory('App\User')->create());
		 
		 // $this->actingAs(create('App\User'));
		 
		 $this->signIn();

		 // Can use raw() to make an array
		 // $thread = factory('App\Thread')->make();

		 /* Mistake by EP9. should not be create, otherwise it has already existed in DB. 
		  * We already know its title and body. Thus the post test below is meaningless   
		  **/
		 $thread = make('App\Thread');

		 $response = $this->post('/threads', $thread->toArray());

		 $this->get($response->headers->get('Location'))
				->assertSee($thread->title)
				->assertSee($thread->body);
	}

	/** @test */
	public function a_thread_requires_a_title()
	{
		$this->publishThread(['title' => null])
			->assertSessionHasErrors('title');

	}

	/** @test */
	public function a_thread_requires_a_body()
	{
		$this->publishThread(['body' => null])
			->assertSessionHasErrors('body');

	}


	/** @test */
	public function a_thread_requires_a_valid_channel()
	{
		factory('App\Channel', 2)->create();

		$this->publishThread(['channel_id' => null])
			->assertSessionHasErrors('channel_id');

		$this->publishThread(['channel_id' => 999])
			->assertSessionHasErrors('channel_id');

	}

	/** @test */
	function unauthorized_users_may_not_delete_threads()
	{
		$this->withExceptionHandling();

		$thread = create('App\Thread');

		// If u use json request here, it will return a 401. (Not a standard request)
		$this->delete($thread->path())
		// Or we can even throw an exception.
			->assertRedirect('/login');

		$this->signIn();

		// $this->delete($thread->path())->assertRedirect('/login');
		$this->delete($thread->path())->assertStatus(403);
	}

	/** @test */
	function authorized_user_can_delete_threads()
	{
		$this->signIn();

		$thread = create('App\Thread', ['user_id' => auth()->id()]);

		$reply = create('App\Reply', ['thread_id' => $thread->id]);

		$response = $this->json('DELETE', $thread->path());

		/**
		 * 204 means we accepted what u gave us, we have nothing to response with.
		 */
		$response->assertStatus(204);

		$this->assertDatabaseMissing('threads', ['id' => $thread->id]);
		$this->assertDatabaseMissing('replies', ['id' => $reply->id]);
	}

	public function publishThread($overrides = [])
	{
	   
	   $this->withExceptionHandling()->signIn();

	   $thread = make('App\Thread', $overrides);

	   return $this->post('/threads', $thread->toArray());

	}
}
