<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A home page test.
     *
     * @return void
     */
    public function testHomePage()
    {
        $response = $this->get(route('home'));
        $response->assertOk();
    }
}
