<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Item;
use AlexWestergaard\PhpGa4\Helper\AbstractIO;
use AlexWestergaard\PhpGa4\Helper\AbstractEvent;
use AlexWestergaard\PhpGa4\Facade\Type\UserProperty;
use AlexWestergaard\PhpGa4\Facade\Type\Ga4Exception;
use AlexWestergaard\PhpGa4\Facade\Type\Event;
use AlexWestergaard\PhpGa4\Exception\Ga4UserPropertyException;
use AlexWestergaard\PhpGa4\Exception\Ga4IOException;
use AlexWestergaard\PhpGa4\Exception\Ga4EventException;
use AlexWestergaard\PhpGa4Test\Class\TestCase;
use AlexWestergaard\PhpGa4Test\Class\TestAbstractUserProperty;
use AlexWestergaard\PhpGa4Test\Class\TestAbstractIO;
use AlexWestergaard\PhpGa4Test\Class\TestAbstractEvent;

final class AbstractTest extends TestCase
{
    public function testAbstractionIoCapabilities()
    {
        $io = new TestAbstractIO();

        $this->assertInstanceOf(AbstractIO::class, $io, get_class($io));
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

    public function testAbstractionIoThrowsOnMissingRequiredParam()
    {
        $io = new TestAbstractIO();

        $this->expectException(Ga4IOException::class);
        $this->expectExceptionCode(Ga4Exception::PARAM_MISSING_REQUIRED);

        $io->toArray();
    }

    public function testAbstractionIoKeepsArrayOnUnset()
    {
        $io = new TestAbstractIO();
        $this->assertIsArray($io['test_array']);
        unset($io['test_array']);
        $this->assertIsArray($io['test_array']);
    }

    public function testAbstractionEventCapabilities()
    {
        $event = new TestAbstractEvent();

        $this->assertInstanceOf(AbstractEvent::class, $event, get_class($event));
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

    public function testAbstractionEventThrowsOnMissingName()
    {
        $event = new class extends TestAbstractEvent
        {
            public function getName(): string
            {
                return '';
            }
        };

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_MISSING);

        $event->toArray();
    }

    public function testAbstractionEventThrowsOnReservedName()
    {
        $event = new class extends TestAbstractEvent
        {
            public function getName(): string
            {
                return Event::RESERVED_NAMES[0];
            }
        };

        $this->expectException(Ga4EventException::class);
        $this->expectExceptionCode(Ga4Exception::EVENT_NAME_RESERVED);

        $event->toArray();
    }

    public function testAbstractionUserPropertyCapabilities()
    {
        $userProperty = new TestAbstractUserProperty();

        $userProperty->setName($name = 'testname');
        $userProperty->setValue($value = 'testvalue');

        $export = $userProperty->toArray();

        $this->assertArrayHasKey($name, $export);
        $this->assertArrayHasKey('value', $export[$name]);
        $this->assertEquals($value, $export[$name]['value']);
    }

    public function testAbstractionUserPropertyThrowsOnReservedName()
    {
        $userProperty = new TestAbstractUserProperty();

        $this->expectException(Ga4UserPropertyException::class);
        $this->expectExceptionCode(Ga4Exception::PARAM_RESERVED);

        $userProperty->setName(UserProperty::RESERVED_NAMES[0]);
    }
}
