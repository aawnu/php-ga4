<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4Test\MeasurementTestCase;

final class HelperTest extends MeasurementTestCase
{
    public function test_convert_helper_transforms_array_into_events()
    {
        $list = Helper\ConvertHelper::parseEvents([
            ['TutorialBegin' => []],
            ['UnlockAchievement' => ['achievement_id' => '123']],
            ['NotAnEvent' => ['skip' => 'me']]
        ]);

        $this->assertIsArray($list);
        $this->assertCount(2, $list);
        $this->assertInstanceOf(Event\TutorialBegin::class, $list[0]);
        $this->assertInstanceOf(Event\UnlockAchievement::class, $list[1]);

        $this->analytics->addEvent(...$list);
        $this->assertCount(2, $this->analytics['events']);
    }

    public function test_convert_helper_transforms_events_with_items()
    {
        $request = [
            [
                'AddToCart' =>  [
                    'currency' => 'AUD',
                    'value' => 38,
                    'items' =>  [
                        [
                            'item_id' => 29,
                            'item_name' => '500g Musk Scrolls',
                            'price' => 38,
                            'quantity' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $list = Helper\ConvertHelper::parseEvents($request);
        $this->analytics->addEvent(...$list);
        $this->assertCount(1, $this->analytics['events']);
    }

    public function test_snakecase_helper_transforms_camelcase_names()
    {
        $output = Helper\ConvertHelper::snake('snakeCase');
        $this->assertEquals('snake_case', $output);
    }

    public function test_camelcase_helper_transforms_snakecase_names()
    {
        $output = Helper\ConvertHelper::camel('snake_case');
        $this->assertEquals('snakeCase', $output);
    }

    public function test_timeasmicro_converts_to_microseconds()
    {
        $time = time();
        $secondAsMicro = 1_000_000;
        $timeAsMicro = $time * $secondAsMicro;

        $convert = Helper\ConvertHelper::timeAsMicro($time);

        $this->assertEquals($timeAsMicro, $convert);
    }

    public function test_timeasmicro_throws_too_large()
    {
        $time = time() * 100;

        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::MICROTIME_INVALID_FORMAT);

        $this->analytics->setTimestampMicros($time);
    }

    public function test_parse_session_cookie_gs1()
    {
        $sessionData = Helper\ConvertHelper::parseSessionCookie('GS1.1.1689053763.1.1.1689054101.0.0.0');
        $this->assertEquals([
            'version' => 'GS1',
            'domain_level' => '1',
            'session_id' => '1689053763',
            'session_number' => '1',
            'session_engagement' => '1',
            'timestampt' => '1689054101',
        ], $sessionData);
    }

    public function test_parse_session_cookie_gs2()
    {
        $sessionData = Helper\ConvertHelper::parseSessionCookie('GS2.1.s1764888982$o50$g1$t1764890260$j59$l0$h681028196');
        $this->assertEquals([
            'session_id' => 's1764888982',
            'session_number' => 'o50',
            'session_engaged' => 'g1',
            'timestamp' => 't1764890260',
            'join_timer' => 'j59',
            'logged_in_state' => 'l0',
            'user_id' => 'h681028196',
        ], $sessionData);
        
        // same data, different order
        $sessionData = Helper\ConvertHelper::parseSessionCookie('GS2.1.t1764890260$o50$g1$s1764888982$j59$l0$h681028196');
        $this->assertEquals([
            'session_id' => 's1764888982',
            'session_number' => 'o50',
            'session_engaged' => 'g1',
            'timestamp' => 't1764890260',
            'join_timer' => 'j59',
            'logged_in_state' => 'l0',
            'user_id' => 'h681028196',
        ], $sessionData);
    }

    public function test_parse_session_cookie_invalid()
    {
        $sessionData = Helper\ConvertHelper::parseSessionCookie('invalid');
        $this->assertEquals([], $sessionData);
        
        $sessionData = Helper\ConvertHelper::parseSessionCookie('');
        $this->assertEquals([], $sessionData);
    }
}
