<?php

namespace Tests\Feature;

use App\User;
use Tests\ResetsDatabase;
use Tests\TestCase;
use Tests\UsesUsers;

class FrontendAuthenticationTest extends TestCase
{
    use ResetsDatabase, UsesUsers;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpUsers();
    }

    public function testLoginPageLoads()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('Login')
        ;
    }

    public function testRegisterPageRedirects()
    {
        $response = $this->get('/register');

        $response->assertStatus(302);

        $this->get($response->getTargetUrl())
            ->assertStatus(200)
            ->assertSee('Login')
        ;
    }
}
