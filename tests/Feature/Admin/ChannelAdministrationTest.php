<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->withExceptionHandling();
    }

    /** @test */
    function an_administrator_can_access_the_channel_administration_section()
    {
        $this->signInAdmin()
            ->get(route('admin.channels.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function non_administrators_cannot_access_the_channel_administration_section()
    {
        $regularUser = create(User::class);
        $this->actingAs($regularUser)
            ->get(route('admin.channels.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
        // $this->actingAs($regularUser)
        //     ->get(route('admin.channels.create'))
        //     ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
