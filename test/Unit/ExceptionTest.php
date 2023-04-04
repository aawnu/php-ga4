<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\Exception\Ga4Exception;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4Test\TestCase;

final class ExceptionTest extends TestCase
{
    public function test_analytics_throws_missing_measurement_id()
    {
        $this->expectException(Ga4Exception::class);
        $this->expectExceptionCode(Ga4Exception::REQUEST_MISSING_MEASUREMENT_ID);

        Analytics::new('', $this->prefill['api_secret'], true)->post();
    }

    public function test_analytics_throws_missing_apisecret()
    {
        $this->expectException(Ga4Exception::class);
        $this->expectExceptionCode(Ga4Exception::REQUEST_MISSING_API_SECRET);

        Analytics::new($this->prefill['measurement_id'], '', true)->post();
    }

    public function test_analytics_throws_on_too_large_request_package()
    {
        $kB = 1024;
        $preparyKB = '';
        while (mb_strlen($preparyKB) < $kB) {
            $preparyKB .= 'AAAAAAAA'; // 1 byte
        }

        $this->expectException(Ga4Exception::class);
        $this->expectExceptionCode(Ga4Exception::REQUEST_TOO_LARGE);

        $userProperty = UserProperty::new()->setName('large_package');

        $overflowValue = '';
        while (mb_strlen(json_encode($userProperty->toArray())) <= ($kB * 131)) {
            $overflowValue .= $preparyKB;
            $userProperty->setValue($overflowValue);
        }

        $this->analytics->addUserProperty($userProperty)->post();
    }
}
