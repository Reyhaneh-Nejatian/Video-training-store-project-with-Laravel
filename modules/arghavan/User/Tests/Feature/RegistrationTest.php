<?php

namespace arghavan\User\Tests\Feature;

use arghavan\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use arghavan\User\Models\User;
use arghavan\User\Services\VerifyCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_can_see_register_form(){

        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_user_can_register(){

        $this->withoutExceptionHandling();

        $response = $this->registerNewUser();

        $response->assertRedirect(route('home'));

        $this->assertCount(1,User::all());
    }

    public function test_user_can_verify_account(){

        $user = User::create([
            'name' => 'arghavan',
            'email' => 'arghavan0121@gmail.com',
            'password' => bcrypt('Rm@0121')
        ]);

        $code = VerifyCodeService::generate();

        VerifyCodeService::store($user->id,$code,now()->addDay());

        auth()->loginUsingId($user->id);

        $this->assertAuthenticated();

        $this->post(route('verification.verify'),[
            'verify_code' => $code
        ]);

        $this->assertEquals(true,$user->fresh()->hasVerifiedEmail());

    }

    public function test_use_have_to_verify_account(){

        $this->registerNewUser();
        $response = $this->get(route('home'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_see_home_page(){

        $this->seed(RolePermissionTableSeeder::class);

        $this->registerNewUser();

        $this->assertAuthenticated();

        auth()->user()->markEmailAsVerified();

        $response = $this->get(route('home'));

        $response->assertOk();
    }



    public function registerNewUser(){
        return $this->post(route('register'),[
            'name' => 'RNM',
            'email' => 'arghavan0121@gmail.com',
            'mobile' => '9376547674',
            'password' => 'Rm!0121',
            'password_confirmation' => 'Rm!0121',
        ]);
    }

}
