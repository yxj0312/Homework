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
	   $this->expectException('Illuminate\Auth\AuthenticationException');

	   $thread = factory('App\Thread')->make();


       $this->post('/threads', $thread->toArray());
	}

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
       $this->actingAs(factory('App\User')->create());

       //can use raw() to make an array
       $thread = factory('App\Thread')->make();


       $this->post('/threads', $thread->toArray());

       $this->get($thread->path())
       		->assertSee($thread->title)
       		->assertSee($thread->body);

    }
}
