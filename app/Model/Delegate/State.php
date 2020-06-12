<?php

declare(strict_types=1);

namespace Model\Delegate;

use Consistence\Enum\Enum;

/**
 * @method string getValue()
 */
class State extends Enum
{
    public const VALID = 'valid';

    public function toString() : string
    {
        return $this->getValue();
    }

    public function __toString() : string
    {
        return $this->getValue();
    }
}
