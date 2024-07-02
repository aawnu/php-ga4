<?php

namespace AlexWestergaard\PhpGa4\Helper;

class UserDataHelper
{
    private ?string $sha256_email_address = null;
    private ?string $sha256_phone_number = null;

    private ?string $sha256_first_name = null;
    private ?string $sha256_last_name = null;
    private ?string $sha256_street = null;
    private ?string $city = null;
    private ?string $region = null;
    private ?string $postal_code = null;
    private ?string $country = null;

    /**
     * @param string $email
     * @return bool
     */
    public function setEmail(string $email): bool
    {
        $email = str_replace(" ", "", mb_strtolower($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

        // https://support.google.com/mail/answer/7436150
        if (
            substr($email, -mb_strlen("@gmail.com")) == "@gmail.com" ||
            substr($email, -mb_strlen("@googlemail.com")) == "@googlemail.com"
        ) {
            $x = explode("@", $email, 2);
            if (substr($x[1], -mb_strlen("googlemail.com")) == "googlemail.com") {
                // https://support.google.com/mail/thread/125577450/gmail-and-googlemail?hl=en
                $x[1] = "gmail.com";
            }
            // https://gmail.googleblog.com/2008/03/2-hidden-ways-to-get-more-from-your.html
            $x[0] = explode("+", $x[0], 2)[0];
            $x[0] = str_replace(".", "", $x[0]);
            $email = implode("@", $x);
        }

        $this->sha256_email_address = hash("sha256", $email);
        return true;
    }

    /**
     * @param int $number International number (without dashes and plus) eg. \
     * "+1-123-4567890" for USA or\
     * "+44-1234-5678900" for UK or\
     * "+45-12345678" for DK
     * @return boolean
     */
    public function setPhone(int $number): bool
    {
        $sNumber = strval($number);
        if (strlen($sNumber) < 3 || strlen($sNumber) > 15) {
            return false;
        }

        $this->sha256_phone_number = hash("sha256", "+{$sNumber}");
        return true;
    }

    /**
     * @param string $firstName Users first name
     * @return bool
     */
    public function setFirstName(string $firstName): bool
    {
        if (empty($firstName)) return false;
        $this->sha256_first_name = hash("sha256", $this->strip($firstName, true));
        return true;
    }

    /**
     * @param string $lastName Users last name
     * @return bool
     */
    public function setLastName(string $lastName): bool
    {
        if (empty($lastName)) return false;
        $this->sha256_last_name = hash("sha256", $this->strip($lastName, true));
        return true;
    }

    /**
     * @param string $street Users street name
     * @return bool
     */
    public function setStreet(string $street): bool
    {
        if (empty($street)) return false;
        $this->sha256_street = hash("sha256", $this->strip($street));
        return true;
    }

    /**
     * @param string $city Users city name
     * @return bool
     */
    public function setCity(string $city): bool
    {
        if (empty($city)) return false;
        $this->city = $this->strip($city, true);
        return true;
    }

    /**
     * @param string $region Users region name
     * @return bool
     */
    public function setRegion(string $region): bool
    {
        if (empty($region)) return false;
        $this->region = $this->strip($region, true);
        return true;
    }

    /**
     * @param string $postalCode Users postal code
     * @return bool
     */
    public function setPostalCode(string $postalCode): bool
    {
        if (empty($postalCode)) return false;
        $this->postal_code = $this->strip($postalCode);
        return true;
    }

    /**
     * @param string $iso Users country (ISO)
     * @return bool
     */
    public function setCountry(string $iso): bool
    {
        if (!CountryIsoHelper::valid($iso)) {
            return false;
        }

        $this->country = mb_strtoupper(trim($iso));
        return false;
    }

    public function toArray(): array
    {
        $res = [];

        if (!empty($this->sha256_email_address)) {
            $res["sha256_email_address"] = $this->sha256_email_address;
        }

        if (!empty($this->sha256_phone_number)) {
            $res["sha256_phone_number"] = $this->sha256_phone_number;
        }

        $addr = [];

        if (!empty($this->sha256_first_name)) {
            $addr["sha256_first_name"] = $this->sha256_first_name;
        }

        if (!empty($this->sha256_last_name)) {
            $addr["sha256_last_name"] = $this->sha256_last_name;
        }

        if (!empty($this->sha256_street)) {
            $addr["sha256_street"] = $this->sha256_street;
        }

        if (!empty($this->city)) {
            $addr["city"] = $this->city;
        }

        if (!empty($this->region)) {
            $addr["region"] = $this->region;
        }

        if (!empty($this->postal_code)) {
            $addr["postal_code"] = $this->postal_code;
        }

        if (!empty($this->country)) {
            $addr["country"] = $this->country;
        }

        if (!empty($this->sha256_phone_number)) {
            $res["sha256_phone_number"] = $this->sha256_phone_number;
        }

        if (count($addr) > 0) {
            $res["address"] = $addr;
        }

        return $res;
    }

    private function strip(string $s, bool $removeDigits = false): string
    {
        $d = $removeDigits ? '0-9' : '';

        $s = preg_replace("[^a-zA-Z{$d}\-\_\.\,\s]", "", $s);
        $s = mb_strtolower($s);
        return trim($s);
    }
}
