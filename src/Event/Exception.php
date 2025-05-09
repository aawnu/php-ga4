<?php

namespace AlexWestergaard\PhpGa4\Event;

use AlexWestergaard\PhpGa4\Helper\EventHelper;
use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Facade\Type\Ga4ExceptionType;

class Exception extends EventHelper implements Facade\Group\ExceptionFacade
{
    protected null|string $description;
    protected null|bool $fatal;

    public function getName(): string
    {
        return 'exception';
    }

    public function getParams(): array
    {
        return [
            'description',
            'fatal',
        ];
    }

    public function getRequiredParams(): array
    {
        return [];
    }

    public function setDescription(null|string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function setFatal(null|bool $isFatal)
    {
        $this->fatal = $isFatal;
        return $this;
    }

    public function parseException(\Exception $exception, $isFatal = false)
    {
        if ($exception instanceof Ga4ExceptionType) {
            return $this;
        }

        $this->setDescription($exception->getMessage());
        $this->setFatal($isFatal);

        return $this;
    }
}
