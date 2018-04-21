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
    public function a_user_can_read_a_single_thread()
    {
        // $thread = factory('App\Thread')->create();

        $this->get('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id)

            ->assertSee($this->thread->title);
    }

    /** @test */
    // public function a_user_can_read_replies_that_are_assosicated_with_a_thread()
    // {
    //     $reply = factory('App\Reply')
    //         ->create(['thread_id' => $this->thread->id]);

    //     $this->get('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id)
    //         ->assertSee($reply->body);

    // }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);

        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        //Give we have three threads
        $threadWithTwoReplies = create('App\Thread');
        $threadWithThreeReplies = create('App\Thread');
        $threadWithNoReplies = $this->thread;
        
        //With 2 replies, 3 replies, 0 replies, respectively
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
        
        //When I filter all threads by popularity
        $response = $this->getJson('threads?popular=1')->json();

        //Then they should be returned from most replies to least.
        $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        //We already had a thread from setUp
        //We created another thread, that had one reply. 
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        /* Php tinker:
        factory('App\Reply', 30)->create(['thread_id' => App\Thread::latest()->first()->id]) */
        $this->assertCount(1, $response['data']);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    function we_record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create('App\Thread');

        $this->assertSame(0, $thread->visits);
        
        // A get request to the thread path
        $this->call('GET', $thread->path());

        // visits is pre-loaded at the time we made a request, 
        // so get a fresh copy from the DB using a fresh()
        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
