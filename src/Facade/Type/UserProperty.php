<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface UserProperty extends IO
{
    /** @return array<int,string> */
    public const RESERVED_NAMES = [
        'first_open_time',
        'first_visit_time',
        'last_deep_link_referrer',
        'user_id',
        'first_open_after_install',
    ];

    /**
     * Set name of User Property
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): static;

    /**
     * Set value of User Property
     *
     * @param int|float|string $value
     *
     * @return static
     */
    public function setValue(int|float|string $value): static;
}
