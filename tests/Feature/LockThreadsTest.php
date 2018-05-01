<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function non_adminstrators_may_not_lock_threads()
    {
        $this->withExceptionHandling();
        
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        // hit an endpoint, that will update the "locked" attribute to true for the thread.
        // $this->patch($thread->path(), [
        $this->post(route('locked-threads.store', $thread)/* , [
            'locked' => true
        ] */)->assertStatus(403);

        // Could refactor with Model casting.
        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    function adminstrators_can_lock_threads()
    {
        // the logic to create an admin.
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        // $this->patch($thread->path(), [
        $this->post(route('locked-threads.store', $thread)
        /* ,[        
            'locked' => true
        ] */);

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread was locked.');
    }

    /** @test */
    function adminstrators_can_unlock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => false]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was unlocked.');
    }


    /** @test */
    function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create('App\Thread', ['locked' => true]);

        // $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
