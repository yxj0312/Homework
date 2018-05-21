<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_administrator_can_access_the_administration_section()
    {
        $this->withExceptionHandling()
            ->signInAdmin()
            ->get(route('admin.dashboard.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function a_non_administrator_cannot_access_the_administration_section()
    {
        $this->withExceptionHandling()
            ->actingAs(create('App\User'))
            ->get(route('admin.dashboard.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
