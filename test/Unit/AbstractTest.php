<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Helper;
use AlexWestergaard\PhpGa4\Facade\Type;
use AlexWestergaard\PhpGa4\Exception;
use AlexWestergaard\PhpGa4Test\TestCase;
use AlexWestergaard\PhpGa4Test\Mocks;

final class AbstractTest extends TestCase
{
    /******************************************************************
     * ABSTRACT IO | INPUT OUTPUT
     */

    public function test_abstract_io_interface_capabilities()
    {
        $io = new Mocks\MockIOHelper();

        $this->assertInstanceOf(Helper\IOHelper::class, $io, get_class($io));
        $this->assertArrayHasKey('test', $io);
        $this->assertArrayHasKey('test_required', $io);
        $this->assertArrayHasKey('test_array', $io);

        $io['test'] = 'optionalTest';
        $this->assertEquals('optionalTest', $io['test']);
        $this->assertEquals('optionalTest', $io->test);

        $io['test_required'] = 'requiredTest';
        $this->assertEquals('requiredTest', $io['test_required']);
        $this->assertEquals('requiredTest', $io->test_required);

        $io['test_array'] = 'optionalArrayElement';
        $this->assertIsArray($io['test_array']);
        $this->assertIsArray($io->test_array);
        /** @var array  */ $test_array = $io['test_array'];
        $this->assertContains('optionalArrayElement', $test_array);
        $this->assertContains('optionalArrayElement', $io->test_array);

        $export = $io->toArray();
        $this->assertIsArray($export);
        $this->assertArrayHasKey('test', $export);
        $this->assertArrayHasKey('test_required', $export);
    }

    public function test_abstract_io_throws_on_missing_required_param()
    {
        $io = new Mocks\MockIOHelper();

        $this->expectException(Exception\Ga4IOException::class);
        $this->expectExceptionCode(Exception\Ga4Exception::PARAM_MISSING_REQUIRED);

        $io->toArray();
    }

    public function test_abstract_io_unsets_array_as_empty_array()
    {
        $io = new Mocks\MockIOHelper();
        $this->assertIsArray($io['test_array']);
        unset($io['test_array']);
        $this->assertIsArray($io['test_array']);
    }

    public function test_abstract_io_can_iterate_as_arrayable()
    {
        $io = new Mocks\MockIOHelper();
        $io['test'] = 'optionalTest';
        $io['test_required'] = 'requiredTest';
        $io['test_array'] = 'optionalArrayElement';

        foreach ($io as $param => $val) {
            $this->assertContains($param, $io->getAllParams());
            $this->assertNotEmpty($val);
        }
    }

    /******************************************************************
     * ABSTRACT EVENT
     */

    public function test_abstract_event_interface_capabilities()
    {
        $event = new Mocks\MockEventHelper();

        $this->assertInstanceOf(Helper\EventHelper::class, $event, get_class($event));
        $this->assertArrayHasKey('test', $event);
        $this->assertArrayHasKey('test_required', $event);
        $this->assertArrayHasKey('test_items', $event);

        $event['test_required'] = 1;

        $event['test_items'] = Item::new()
            ->setItemId('1')
            ->setItemName('First Product')
            ->setCurrency('DKK')
            ->setPrice(7.39)
            ->setQuantity(2);

        $this->assertIsArray($event['test_items'], 'arrayable');
        $this->assertIsArray($event->test_items);

        $export = $event->toArray();
        $this->assertIsArray($export);
        $this->assertArrayHasKey('name', $export);
        $this->assertArrayHasKey('params', $export);

        $exportParams = $export['params'];
        $this->assertArrayNotHasKey('test', $exportParams);
        $this->assertArrayHasKey('test_required', $exportParams);
        $this->assertArrayHasKey('test_items', $exportParams);
        $this->assertCount(2, $exportParams);
        $this->assertCount(1, $exportParams['test_items']);
    }

    public function test_abstract_event_throws_on_missing_name()
    {
        $event = new class extends Mocks\MockEventHelper
        {
            public function getName(): string
            {
                return '';
            }
        };

        $this->expectException(Exception\Ga4EventException::class);
        $this->expectExceptionCode(Exception\Ga4Exception::EVENT_NAME_MISSING);

        $event->toArray();
    }

    public function test_abstract_event_throws_on_reserved_name()
    {
        $event = new class extends Mocks\MockEventHelper
        {
            public function getName(): string
            {
                return Type\EventType::RESERVED_NAMES[0];
            }
        };

        $this->expectException(Exception\Ga4EventException::class);
        $this->expectExceptionCode(Exception\Ga4Exception::EVENT_NAME_RESERVED);

        $event->toArray();
    }
}
