<?php

declare(strict_types=1);

namespace Model\Vote;

use Consistence\Enum\Enum;

/**
 * @method string getValue()
 */
class ConfigItem extends Enum
{
    public const VOTING_BEGIN = 'voting_begin';
    public const VOTING_END   = 'voting_end';

    public function toString() : string
    {
        return $this->getValue();
    }

    public function __toString() : string
    {
        return $this->getValue();
    }

    public static function VOTING_BEGIN() : self
    {
        return self::get(self::VOTING_BEGIN);
    }

    public static function VOTING_END() : self
    {
        return self::get(self::VOTING_END);
    }
}
