<?php

namespace AlexWestergaard\PhpGa4\Helper;

class ConsentHelper
{
    const GRANTED = "granted";
    const DENIED = "denied";

    private ?string $ad_user_data = null;
    private ?string $ad_personalization = null;

    public function clearAdUserDataPermission(): self
    {
        $this->ad_user_data = null;
        return $this;
    }

    public function setAdUserDataPermission(bool $allow = false): self
    {
        $this->ad_user_data = $allow ? self::GRANTED : self::DENIED;
        return $this;
    }

    public function getAdUserDataPermission(): null|string
    {
        return $this->ad_user_data == null ? null : $this->ad_user_data;
    }

    public function clearAdPersonalizationPermission(): self
    {
        $this->ad_personalization = null;
        return $this;
    }

    public function setAdPersonalizationPermission(bool $allow = false): self
    {
        $this->ad_personalization = $allow ? self::GRANTED : self::DENIED;
        return $this;
    }

    public function getAdPersonalizationPermission(): null|string
    {
        return $this->ad_personalization == null ? null : $this->ad_personalization;
    }

    public function toArray(): array
    {
        $e = [];

        if ($this->ad_user_data != null) {
            $e["ad_user_data"] = $this->getAdUserDataPermission();
        }

        if ($this->ad_personalization != null) {
            $e["ad_personalization"] = $this->getAdPersonalizationPermission();
        }

        return $e;
    }
}
