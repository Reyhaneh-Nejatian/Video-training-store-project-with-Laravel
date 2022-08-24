<?php

namespace arghavan\User\Tests\Unit;

use arghavan\User\Rules\ValidPassword;
use PHPUnit\Framework\TestCase;

class PasswordValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_password_should_not_be_less_than_6_character()
    {
        $result = (new ValidPassword())->passes('','Rm@10');
        $this->assertEquals('0',$result);
    }

    public function test_password_should_include_sign_character()
    {
        $result = (new ValidPassword())->passes('','Rm0121');
        $this->assertEquals('0',$result);
    }

    public function test_password_should_include_digit_character()
    {
        $result = (new ValidPassword())->passes('','Rm@$Mr');
        $this->assertEquals('0',$result);
    }

    public function test_password_should_include_Capital_character()
    {
        $result = (new ValidPassword())->passes('','rm@0121');
        $this->assertEquals('0',$result);
    }

    public function test_password_should_include_small_character()
    {
        $result = (new ValidPassword())->passes('','RM@0121');
        $this->assertEquals('0',$result);
    }
}
