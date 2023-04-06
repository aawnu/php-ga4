<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\Exception;
use AlexWestergaard\PhpGa4Test\TestCase;

final class UserPropertyTest extends TestCase
{
    public function test_can_configure_and_export()
    {
        $userProperty = new UserProperty();

        $userProperty->setName($name = 'testname');
        $userProperty->setValue($value = 'testvalue');

        $export = $userProperty->toArray();

        $this->assertArrayHasKey($name, $export);
        $this->assertArrayHasKey('value', $export[$name]);
        $this->assertEquals($value, $export[$name]['value']);
    }

    public function test_throws_on_reserved_name()
    {
        $userProperty = new UserProperty();

        $this->expectException(Exception\Ga4UserPropertyException::class);
        $this->expectExceptionCode(Exception\Ga4Exception::PARAM_RESERVED);

        $userProperty->setName(UserProperty::RESERVED_NAMES[0]);
    }

    public function test_throws_on_too_long_name()
    {
        $userProperty = new UserProperty();

        $this->expectException(Exception\Ga4UserPropertyException::class);
        $this->expectExceptionCode(Exception\Ga4Exception::PARAM_TOO_LONG);

        $tooLongName = '';
        while (mb_strlen($tooLongName) <= 24) {
            $tooLongName .= range('a', 'z')[rand(0, 25)];
        }

        $userProperty->setName($tooLongName);
    }
}
