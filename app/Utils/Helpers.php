<?php

declare(strict_types=1);

namespace App\Utils;

use DateTimeImmutable;

class Helpers
{
    public const DATE_FORMAT = 'j. n. Y';

    public const DATETIME_FORMAT = 'j. n. Y H:i';

    public static function formatDate(DateTimeImmutable $datetime) : string
    {
        return $datetime->format(self::DATE_FORMAT);
    }

    public static function formatDateTime(DateTimeImmutable $datetime) : string
    {
        return $datetime->format(self::DATETIME_FORMAT);
    }
}
