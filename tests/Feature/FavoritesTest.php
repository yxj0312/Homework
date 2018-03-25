<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function a_guest_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        /**
         * /threads/channle/id/replies/id/favorites
         * /replies/id/favorites
         * /replies/id/favorite
         * /favorties <-- reply_id in the request
         */
        $this->signIn();

        $reply = create('App\Reply');
        
        // If I post to a "favorite" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        // It should be recorded in the database.
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_can_unfavorite_a_reply()
    {
        /**
         * /threads/channle/id/replies/id/favorites
         * /replies/id/favorites
         * /replies/id/favorite
         * /favorties <-- reply_id in the request
         */
        $this->signIn();

        $reply = create('App\Reply');

        // Use the api directly and refactor.
        $reply->favorite();
        
        // If I post to a "favorite" endpoint
        // $this->post('replies/' . $reply->id . '/favorites');

        // $this->assertCount(1, $reply->favorites);

        $this->delete('replies/' . $reply->id . '/favorites');
        
        // With that case below, we already eager load the favorites, so it will not be updated
        // $this->assertCount(0, $reply->favorites);
        // fresh() means get a fresh instance, basically reset everything.
        // $this->assertCount(0, $reply->fresh()->favorites);
        $this->assertCount(0, $reply->favorites);
        
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');
        
        try {    
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }
        

        // It should be recorded in the database.
        $this->assertCount(1, $reply->favorites); 
    }
}
