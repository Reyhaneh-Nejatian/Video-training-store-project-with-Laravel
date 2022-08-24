<?php

namespace arghavan\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //چک کردن باز شدن صفحه پسورد ریکوئست
    public function test_user_can_see_reset_password_request_form(){

        $this->get(route('password.request'))->assertOk();
    }

    //چک کردن باز شدن صفحه پسورد سند وریفای کد و همراه با ایمیل
    public function test_user_can_see_enter_verify_code_from_by_correct_email(){

        $this->call('get',route('password.sendVerifyCodeEmail'),['email' => 'arghavan0121@gmail.com'])
        ->assertOk();
    }

    //چک کردن باز شدن صفحه پسورد سند وریفای کد و همراه با ایمیل اشتباه
    public function test_user_cannot_see_enter_verify_code_from_by_wrong_email(){

        $this->call('get',route('password.sendVerifyCodeEmail'),['email' => 'arghavan0121.com'])
            ->assertStatus(302);
    }

    // چک کردن بعد از 5 بار امتحان کردن بن بشود
    public function test_user_banned_after_6_attempt_to_reset_password(){

        for($i = 0; $i < 5; $i++){
            $this->post(route('password.checkVerifyCode'),['verify_code','email' => 'arghavan0121@gmail.com'])->assertStatus(302);
        }
        $this->post(route('password.checkVerifyCode'),['verify_code','email' => 'arghavan0121@gmail.com'])->assertStatus(429);

    }

}
