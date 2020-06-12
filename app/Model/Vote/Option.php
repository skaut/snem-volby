<?php

declare(strict_types=1);

namespace Model\Vote;

use Consistence\Enum\Enum;

/**
 * @method string getValue()
 */
class Option extends Enum
{
    public const YES     = 'yes';
    public const NO      = 'no';
    public const ABSTAIN = 'abstain';

    public function toString() : string
    {
        return $this->getValue();
    }

    public function __toString() : string
    {
        return $this->getValue();
    }

    public static function YES() : self
    {
        return self::get(self::YES);
    }

    public static function NO() : self
    {
        return self::get(self::NO);
    }

    public static function ABSTAIN() : self
    {
        return self::get(self::ABSTAIN);
    }
}
