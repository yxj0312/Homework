<?php

namespace Tests\Feature;

use App\Thread;
use App\Activity;
use Tests\TestCase;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();

		app()->singleton(Recaptcha::class, function () {
			return \Mockery::mock(Recaptcha::class, function($m){

			// If it recevie it should call the 'passes' method, it should return true.
        		$m->shouldReceive('passes')->andReturn(true);
			});
		});
	}

	/** @test */
	public function guest_may_not_create_threads()
	{

		$this->withExceptionHandling();

		$this->get('threads/create')
			->assertRedirect(route('login'));

	  	$this->post(route('threads'))
	   		->assertRedirect(route('login'));
 

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
	function new_users_must_first_confirm_their_email_address_before_creating_threads()
	{
		$user = factory('App\User')->states('unconfirmed')->create();
		
		$this->signIn($user);

        $thread = make('App\Thread');

    	$this->post(route('threads'), $thread->toArray())
			->assertRedirect(route('threads'))
			// U can just assertSessionHas 'flash', without the flash message.
			->assertSessionHas('flash', 'You must first confirm your email address.');

	}

	/** @test */
	public function a_user_can_create_new_forum_threads()
	{
		 // $this->actingAs(factory('App\User')->create());
		 
		 // $this->actingAs(create('App\User'));
		 
		//  $this->signIn();

		 // Can use raw() to make an array
		 // $thread = factory('App\Thread')->make();

		 /* Mistake by EP9. should not be create, otherwise it has already existed in DB. 
		  * We already know its title and body. Thus the post test below is meaningless   
		  **/
		//  $thread = make('App\Thread');

		//  $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);

		 $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some body']);

		//  dd($response->headers->get('Location'));

		 $this->get($response->headers->get('Location'))
				// ->assertSee($thread->title)
				->assertSee('Some Title')
				// ->assertSee($thread->body)
				->assertSee('Some body');
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
	function a_thread_requires_recaptcha_verification()
	{
		// Unbind to the container. or app()->offsetUnset();
        // Then this test will trigger the logic of passes method in Recaptcha rules
        unset(app()[Recaptcha::class]);

		$this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');

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
	function a_thread_requires_a_unique_slug()
	{
		$this->signIn();
		
		// Create two random threads. 
		// For assurance that we can pass all situation, cause it always create from id= 1 without this assurance
		// comment it again after all passed.
		/* create('App\Thread', [], 2); */

		$thread = create('App\Thread', ['title' => 'Foo Title']);
		
		// Refactor: using a model event to instead of using model factory to assign the slug
		// See boot method in Thread.php
		$this->assertEquals($thread->fresh()->slug, 'foo-title');

		/* $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);
		$this->assertEquals($thread->fresh()->slug, 'foo-title'); */
		
		// If slug not nullable, it will be fail, cause we are not setting slug in modelfactory, when $thread is created.
		/* $this->post(route('threads'), $thread->toArray()); */

		// We could do Thread::latest('id')
		// or
		$thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

		// dd($thread['id']);
		/* $this->assertTrue(Thread::whereSlug('foo-title-2')->exists()); */
		$this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);

		// $this->post(route('threads'), $thread->toArray());

		// $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
	}

	/** @test */
	function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
	{
		$this->signIn();

        /* $thread = create('App\Thread', ['title' => 'Some Title 24', 'slug' => 'some-title-24']); */
        $thread = create('App\Thread', ['title' => 'Some Title 24']);
		
		/* $this->post(route('threads'), $thread->toArray());
		$this->assertTrue(Thread::whereSlug('some-title-24-2')->exists()); */

		$thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
		$this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);

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

		$this->assertEquals(0, Activity::count());
		/* $this->assertDatabaseMissing('activities', [
			'subject_id' => $thread->id,
			'subject_type' => get_class($thread)
		]);
		$this->assertDatabaseMissing('activities', [
			'subject_id' => $reply->id,
			'subject_type' => get_class($reply)
		]); */
		
	}

	public function publishThread($overrides = [])
	{
	   
	   $this->withExceptionHandling()->signIn();

	   $thread = make('App\Thread', $overrides);

	   return $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);
	}
}
