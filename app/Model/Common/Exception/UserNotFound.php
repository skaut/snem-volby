<?php

declare(strict_types=1);

namespace Model\Common;

use Exception;
use Throwable;

final class UserNotFound extends Exception
{
    public static function fromPrevious(Throwable $previous) : self
    {
        return new self($previous->getMessage(), $previous->getCode(), $previous);
    }
}
