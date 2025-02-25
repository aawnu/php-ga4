<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\UserProperty;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Event;
use AlexWestergaard\PhpGa4\Firebase;
use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4Test\FirebaseTestCase;

final class FirebaseTest extends FirebaseTestCase
{
    public function test_can_configure_only_client_id_and_export()
    {
        $analytics = Firebase::new(
            $this->prefill['firebase_app_id'],
            $this->prefill['api_secret'],
            $debug = true
        )
            ->setAppInstanceId($this->prefill['app_instance_id'])
            ->setTimestampMicros($time = time())
            ->addEvent($event = Event\JoinGroup::fromArray(['group_id' => 1]))
            ->addUserProperty($userProperty = UserProperty::fromArray(['name' => 'test', 'value' => 'testvalue']));

        $asArray = $analytics->toArray();
        $this->assertIsArray($asArray);

        $this->assertArrayHasKey('timestamp_micros', $asArray);
        $this->assertArrayHasKey('app_instance_id', $asArray);
        $this->assertArrayNotHasKey('user_id', $asArray);
        $this->assertArrayHasKey('user_properties', $asArray);
        $this->assertArrayHasKey('events', $asArray);

        $timeAsMicro = $time * 1_000_000;

        $this->assertEquals($timeAsMicro, $asArray['timestamp_micros']);
        $this->assertEquals($this->prefill['app_instance_id'], $asArray['app_instance_id']);
        $this->assertEquals($userProperty->toArray(), $asArray['user_properties']);
        $this->assertEquals([$event->toArray()], $asArray['events']);
    }

    public function test_can_configure_and_export()
    {
        $analytics = Firebase::new(
            $this->prefill['firebase_app_id'],
            $this->prefill['api_secret'],
            $debug = true
        )
            ->setAppInstanceId($this->prefill['app_instance_id'])
            ->setUserId($this->prefill['user_id'])
            ->setTimestampMicros($time = time())
            ->addEvent($event = Event\JoinGroup::fromArray(['group_id' => 1]))
            ->addUserProperty($userProperty = UserProperty::fromArray(['name' => 'test', 'value' => 'testvalue']));

        $asArray = $analytics->toArray();
        $this->assertIsArray($asArray);

        $this->assertArrayHasKey('timestamp_micros', $asArray);
        $this->assertArrayHasKey('app_instance_id', $asArray);
        $this->assertArrayHasKey('user_id', $asArray);
        $this->assertArrayHasKey('user_properties', $asArray);
        $this->assertArrayHasKey('events', $asArray);

        $timeAsMicro = $time * 1_000_000;

        $this->assertEquals($timeAsMicro, $asArray['timestamp_micros']);
        $this->assertEquals($this->prefill['app_instance_id'], $asArray['app_instance_id']);
        $this->assertEquals($this->prefill['user_id'], $asArray['user_id']);
        $this->assertEquals($userProperty->toArray(), $asArray['user_properties']);
        $this->assertEquals([$event->toArray()], $asArray['events']);
    }

    public function test_can_post_only_client_id_to_google()
    {
        $this->expectNotToPerformAssertions();
        Firebase::new(
            $this->prefill['firebase_app_id'],
            $this->prefill['api_secret'],
            $debug = true
        )
            ->setAppInstanceId($this->prefill['app_instance_id'])
            ->addEvent(Login::new())
            ->post();
    }

    public function test_can_post_to_google()
    {
        $this->expectNotToPerformAssertions();
        $this->firebase->addEvent(Login::new())->post();
    }

    public function test_converts_to_full_microtime_stamp()
    {
        $this->firebase->setTimestampMicros(microtime(true));

        $arr = $this->firebase->toArray();

        $this->assertTrue($arr['timestamp_micros'] > 1_000_000);
    }

    public function test_throws_if_microtime_older_than_three_days()
    {
        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::MICROTIME_EXPIRED);

        $this->firebase->setTimestampMicros(strtotime('-1 week'));
    }

    public function test_exports_userproperty_to_array()
    {
        $this->firebase->addEvent(Login::new());

        $userProperty = UserProperty::new()
            ->setName('customer_tier')
            ->setValue('premium');

        $this->assertInstanceOf(UserProperty::class, $userProperty);
        $this->assertIsArray($userProperty->toArray());

        $this->firebase->addUserProperty($userProperty);

        $arr = $this->firebase->toArray();
        $this->assertArrayHasKey('user_properties', $arr);

        $arr = $arr['user_properties'];
        $this->assertArrayHasKey('customer_tier', $arr);

        $this->firebase->post();
    }

    public function test_exports_events_to_array()
    {
        $event = Event\JoinGroup::new()
            ->setGroupId('1');

        $this->assertInstanceOf(Facade\Type\EventType::class, $event);
        $this->assertIsArray($event->toArray());

        $this->firebase->addEvent($event);

        $arr = $this->firebase->toArray();
        $this->assertArrayHasKey('events', $arr);
        $this->assertCount(1, $arr['events']);

        $this->firebase->post();
    }

    public function test_throws_missing_firebase_app_id()
    {
        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::REQUEST_MISSING_FIREBASE_APP_ID);

        Firebase::new('', $this->prefill['api_secret'], true)->post();
    }

    public function test_throws_missing_apisecret()
    {
        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::REQUEST_MISSING_API_SECRET);

        Firebase::new($this->prefill['firebase_app_id'], '', true)->post();
    }

    public function test_throws_on_too_large_request_package()
    {
        $kB = 1024;
        $preparyKB = '';
        while (mb_strlen($preparyKB) < $kB) {
            $preparyKB .= 'AAAAAAAA'; // 8 bytes
        }

        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::REQUEST_TOO_LARGE);

        $userProperty = UserProperty::new()->setName('large_package');

        $overflowValue = '';
        while (mb_strlen(json_encode($userProperty->toArray())) <= ($kB * 131)) {
            $overflowValue .= $preparyKB;
            $userProperty->setValue($overflowValue);
        }

        $this->firebase->addEvent(Login::new())->addUserProperty($userProperty)->post();
    }

    public function test_timeasmicro_throws_exceeding_max()
    {
        $time = time() + 60;

        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::MICROTIME_EXPIRED);

        $this->firebase->setTimestampMicros($time);
    }

    public function test_timeasmicro_throws_exceeding_min()
    {
        $time = strtotime('-1 month');

        $this->expectException(Facade\Type\Ga4ExceptionType::class);
        $this->expectExceptionCode(Facade\Type\Ga4ExceptionType::MICROTIME_EXPIRED);

        $this->firebase->setTimestampMicros($time);
    }
}
