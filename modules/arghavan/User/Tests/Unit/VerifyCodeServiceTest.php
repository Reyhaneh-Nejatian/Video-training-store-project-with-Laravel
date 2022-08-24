<?php


namespace arghavan\User\Tests\Unit;

use arghavan\User\Services\VerifyCodeService;
use Tests\TestCase;


class VerifyCodeServiceTest extends TestCase
{
    public function test_generated_code_is_6_digit(){

        $code = VerifyCodeService::generate();
        $this->assertIsNumeric($code,'Generated code is not Numeric');
        $this->assertLessThanOrEqual(999999,$code,'Generated code is less than 999999');
        $this->assertGreaterThanOrEqual(100000,$code,'Generated code is Greater than 100000');
    }

    public function test_verify_code_can_store(){
        $code = VerifyCodeService::generate();
        VerifyCodeService::store(1,$code,120);

        $this->assertEquals($code,cache()->get('verify_code_1'));
    }
}
