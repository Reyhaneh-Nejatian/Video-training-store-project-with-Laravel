<?php

namespace arghavan\User\Tests\Unit;

use arghavan\User\Rules\ValidMobile;
use PHPUnit\Framework\TestCase;

class MobileValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_mobile_can_not_be_less_than_10_character()
    {
        $result = (new ValidMobile())->passes('','937881610');
        $this->assertEquals('0',$result);
    }

    public function test_mobile_can_not_be_more_than_10_character()
    {
        $result = (new ValidMobile())->passes('','93788161080');
        $this->assertEquals('0',$result);
    }

    public function test_mobile_should_start_by_9()
    {
        $result = (new ValidMobile())->passes('','3378816108');
        $this->assertEquals('0',$result);
    }
}
