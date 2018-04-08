<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Spam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamTest extends TestCase
{
    // Don't need RefreshDatabase trait

    /** @test */
    function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));
    }
}
