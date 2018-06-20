<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_has_an_owner()
    {
    	$reply = factory('App\Reply')->create();   
    	
    	$this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new \App\Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    function it_wraps_mentioned_username_in_the_body_within_anchor_tags()
    {
        // This is faster twice than factory.
        $reply = new \App\Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);
        
        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());        
    }

    /** @test */
  function a_reply_body_is_sanitzized_automatically()
  {
      $reply = make('App\Reply', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

      $this->assertEquals("<p>This is okay.</p>", $reply->body);
  }

    /** @test */
    public function it_generates_the_correct_path_for_a_paginated_thread()
    {
        // Given we have a thread.
        $thread = create('App\Thread');

        // And that thread has three replies.
        $replies = create('App\Reply', ['thread_id' => $thread->id], 3);

        // And we are paginating 1 per page
        config(['homework.pagination.perPage' => 1]);

        // If we generate the path for the last reply(3nd one).
        // It should include ?page=3 in the path.
        $this->assertEquals(
            $thread->path() . '?page=1#reply-1',
            $replies->first()->path()
        );

        $this->assertEquals(
            $thread->path() . '?page=2#reply-2',
            $replies[1]->path()
        );
        
        $this->assertEquals(
            $thread->path() . '?page=3#reply-3',
            $replies->last()->path()
        );
    }

}
