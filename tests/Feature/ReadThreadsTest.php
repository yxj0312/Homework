<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
       parent::setUp();

       $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        // $thread = factory('App\Thread')->create();

        $response = $this->get('/threads')
        
                    ->assertSee($this->thread->title);

        // $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_read_a_single_thread() {
        // $thread = factory('App\Thread')->create();

        $this->get('/threads/'. $this->thread->channel->slug . '/' . $this->thread->id)
        
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_assosicated_with_a_thread()
    {
        $reply = factory('App\Reply')
                ->create(['thread_id' => $this->thread->id]);

        $this->get('/threads/'. $this->thread->channel->slug . '/' . $this->thread->id)
            ->assertSee($reply->body);

    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel() 
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread',['channel_id'=>$channel->id]);
        $threadNotInChannel = create('App\Thread');
         
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);

        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
