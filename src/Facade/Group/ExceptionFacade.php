<?php

namespace AlexWestergaard\PhpGa4\Facade\Group;

use Exception;

interface ExceptionFacade
{
    /**
     * Report exception data
     *
     * @var description
     * @param string $description eg. Exception->Message()
     */
    public function setDescription(null|string $description);

    /**
     * Report if the exception is fatal
     *
     * @var fatal
     * @param bool $isFatal
     */
    public function setFatal(null|bool $isFatal);

    /**
     * Attempt to parse the message from the Exception and error own known GA4 Exceptions
     *
     * @param \Exception $exception
     */
    public function parseException(Exception $exception);
}
