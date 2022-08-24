<?php

namespace arghavan\User\Tests\Feature;


use arghavan\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_can_login_by_email()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt('Rm@0121')
        ]);

        $this->post(route('login'),[
            'email' => $user->email,
            'password' => 'Rm@0121'
        ]);

        $this->assertAuthenticated();

    }

    public function test_user_can_login_by_mobile()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'mobile' => '9378816108',
            'password' => bcrypt('Rm@0121'),
        ]);

        $this->post(route('login'),[
            'email' => $user->mobile,
            'password' => 'Rm@0121'
        ]);

        $this->assertAuthenticated();

    }

}
