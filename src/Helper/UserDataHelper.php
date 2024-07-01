<?php

namespace AlexWestergaard\PhpGa4\Helper;

class UserDataHelper
{
    private array $sha256_email_address = [];

    /**
     * @param string $email Valid email format
     * @return int Cursor of array or -1 for error
     */
    public function addEmail(string $email): int
    {
        // Sanitize & Validate email
        $email = str_replace(" ", "", $email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return -1; // Invalid email format

        // Google Mail Sanitizer
        // https://support.google.com/mail/answer/7436150
        if (
            substr($email, -mb_strlen("@gmail.com")) == "@gmail.com" ||
            substr($email, -mb_strlen("@googlemail.com")) == "@googlemail.com"
        ) {
            $x = explode("@", $email, 2);
            if (substr($x[1], -mb_strlen("googlemail.com")) == "googlemail.com") {
                // https://support.google.com/mail/thread/125577450/gmail-and-googlemail?hl=en
                $x[1] = "gmail.com"; // 
            }
            $x[0] = explode("+", $x[0], 2)[0]; // https://gmail.googleblog.com/2008/03/2-hidden-ways-to-get-more-from-your.html
            $x[0] = str_replace($x[0], ".", "");
            $email = implode("@", $x);
        }

        // Sha256 encode
        $email = hash("sha256", mb_strtolower($email));

        // Append email to user
        array_push($this->sha256_email_address, $email);
        $this->sha256_email_address = array_unique($this->sha256_email_address);
        return array_search($email, $this->sha256_email_address, true);
    }
}
