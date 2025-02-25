<?php

namespace AlexWestergaard\PhpGa4Test\Unit;

use AlexWestergaard\PhpGa4\Event\Login;
use AlexWestergaard\PhpGa4\Helper\UserDataHelper;
use AlexWestergaard\PhpGa4Test\MeasurementTestCase;

final class UserDataTest extends MeasurementTestCase
{
    public function test_user_data_is_fillable()
    {
        $uda = new UserDataHelper();
        $this->assertTrue($uda->setEmail($setEmail = "test@gmail.com"));
        $this->assertTrue($uda->setPhone($setPhone = 4500000000));
        $this->assertTrue($uda->setFirstName($setFirstName = "test"));
        $this->assertTrue($uda->setLastName($setLastName = "person"));
        $this->assertTrue($uda->setStreet($setStreet = "some street 11"));
        $this->assertTrue($uda->setCity($setCity = "somewhere"));
        $this->assertTrue($uda->setRegion($setRegion = "inthere"));
        $this->assertTrue($uda->setPostalCode($setPostalCode = "1234"));
        $this->assertTrue($uda->setCountry($setCountry = "DK"));

        $export = $uda->toArray();
        $this->assertIsArray($export);
        $this->assertEquals(hash("sha256", $setEmail), $export["sha256_email_address"], $setEmail);
        $this->assertEquals(hash("sha256", '+' . $setPhone), $export["sha256_phone_number"], $setPhone);

        $this->assertArrayHasKey("address", $export);
        $this->assertIsArray($export["address"]);
        $this->assertEquals(hash("sha256", $setFirstName), $export["address"]["sha256_first_name"], $setFirstName);
        $this->assertEquals(hash("sha256", $setLastName), $export["address"]["sha256_last_name"], $setLastName);
        $this->assertEquals(hash("sha256", $setStreet), $export["address"]["sha256_street"], $setStreet);
        $this->assertEquals($setCity, $export["address"]["city"], $setCity);
        $this->assertEquals($setRegion, $export["address"]["region"], $setRegion);
        $this->assertEquals($setPostalCode, $export["address"]["postal_code"], $setPostalCode);
        $this->assertEquals($setCountry, $export["address"]["country"], $setCountry);
    }
    public function test_user_data_is_sendable()
    {
        $uad = $this->analytics->userdata();
        $uad->setEmail("test@gmail.com");
        $uad->setPhone(4500000000);
        $uad->setFirstName("test");
        $uad->setLastName("person");
        $uad->setStreet("some street 11");
        $uad->setCity("somewhere");
        $uad->setRegion("inthere");
        $uad->setPostalCode("1234");
        $uad->setCountry("DK");

        $this->analytics->addEvent(Login::new());
        $this->assertNull($this->analytics->post());
    }
}
