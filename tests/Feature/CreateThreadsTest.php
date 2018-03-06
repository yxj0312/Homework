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

			 //can use raw() to make an array
			 // $thread = factory('App\Thread')->make();

			 $thread = create('App\Thread');

			 $this->post('/threads', $thread->toArray());

			 // dd($thread->path());

			 $this->get($thread->path())
					->assertSee($thread->title)
					->assertSee($thread->body);
		}
}
