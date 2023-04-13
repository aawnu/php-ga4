<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4Test\TestCase;

final class HelperTest extends TestCase
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
}
