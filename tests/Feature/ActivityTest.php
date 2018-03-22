<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_record_an_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        // One for creating Thread, one for Reply.
        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        // Given we have a thread
        $this->signIn();

        create('App\Thread', ['user_id'=>auth()->id()], 2);

        // And another thread from a week ago
        // create('App\Thread', ['user_id' => auth()->id(), 'created_at' => Carbon::now()->subWeek()]);
        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        // When we fetch their feed
        $feed = Activity::feed(auth()->user());
        
        // dd($feed->toArray());

        // Then, it should be returned in the proper format.
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        /* This test didn't pass, because we do have a subject from a test ago,
        but the date of the activity associated with it is still now.
        That's because the way we created the trait.
        That's fine in every situation, otherwise in our test.
        So we should not create 2 threads with different date, instead, update one activity to a week ago(use above)*/ 
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
