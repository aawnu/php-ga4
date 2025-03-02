<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4\Helper\ConsentHelper;
use AlexWestergaard\PhpGa4Test\MeasurementTestCase;

final class ConsentTest extends MeasurementTestCase
{
    public function test_no_consent_is_empty()
    {
        $this->analytics->addEvent(Login::new());

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(0, $export);
    }

    public function test_consent_ad_user_data_granted()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(true);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(1, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayNotHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_user_data"]);
    }

    public function test_consent_ad_personalization_granted()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdPersonalizationPermission(true);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(1, $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertArrayNotHasKey("ad_user_data", $export);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_personalization"]);
    }

    public function test_consent_granted()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(true);
        $this->analytics->consent()->setAdPersonalizationPermission(true);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(2, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_user_data"]);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_personalization"]);
    }

    public function test_consent_granted_posted()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(true);
        $this->analytics->consent()->setAdPersonalizationPermission(true);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(2, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_user_data"]);
        $this->assertEquals(ConsentHelper::GRANTED, $export["ad_personalization"]);
        $this->analytics->post();
    }

    public function test_consent_ad_user_data_denied()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(false);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(1, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayNotHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_user_data"]);
    }

    public function test_consent_ad_personalization_denied()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdPersonalizationPermission(false);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(1, $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertArrayNotHasKey("ad_user_data", $export);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_personalization"]);
    }

    public function test_consent_denied()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(false);
        $this->analytics->consent()->setAdPersonalizationPermission(false);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(2, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_user_data"]);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_personalization"]);
    }

    public function test_consent_denied_posted()
    {
        $this->analytics->addEvent(Login::new());

        $this->analytics->consent()->setAdUserDataPermission(false);
        $this->analytics->consent()->setAdPersonalizationPermission(false);

        $export = $this->analytics->consent()->toArray();
        $this->assertIsArray($export);
        $this->assertCount(2, $export);
        $this->assertArrayHasKey("ad_user_data", $export);
        $this->assertArrayHasKey("ad_personalization", $export);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_user_data"]);
        $this->assertEquals(ConsentHelper::DENIED, $export["ad_personalization"]);
        $this->analytics->post();
    }
}
