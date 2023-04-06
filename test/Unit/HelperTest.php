<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4Test\TestCase;

final class HelperTest extends TestCase
{
    public function test_convert_helper_transforms_array_into_events()
    {
        $list = Helper\Converter::parseEvents([
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

    public function test_snakecase_helper_transforms_camelcase_names()
    {
        $output = Helper\Converter::snake('snakeCase');
        $this->assertEquals('snake_case', $output);
    }

    public function test_camelcase_helper_transforms_snakecase_names()
    {
        $output = Helper\Converter::camel('snake_case');
        $this->assertEquals('snakeCase', $output);
    }
}
