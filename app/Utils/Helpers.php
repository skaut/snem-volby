<?php

declare(strict_types=1);

namespace App\Utils;

use DateInterval;
use DateTimeImmutable;

class Helpers
{
    public const DATE_FORMAT = 'j. n. Y';

    public const DATETIME_FORMAT = 'j. n. Y G:i';

    public static function formatDate(DateTimeImmutable $datetime) : string
    {
        return $datetime->format(self::DATE_FORMAT);
    }

    public static function formatDateTime(DateTimeImmutable $datetime) : string
    {
        return $datetime->format(self::DATETIME_FORMAT);
    }

    public static function formatTimeToText(DateInterval $dateInterval) : string
    {
        $result  = '';
        $days    = $dateInterval->d;
        $hours   = $dateInterval->h;
        $minutes = $dateInterval->i;

        if ($days === 1) {
            $result .= $days . ' den ';
        } elseif ($days >=2 && $days < 5) {
            $result .= $days . ' dny ';
        } elseif ($days < 1 || $days >= 5) {
            $result .= $days . ' dní ';
        }

        if ($hours === 1) {
            $result .= $hours . ' hodina ';
        } elseif ($hours >=2 && $hours < 5) {
            $result .= $hours . ' hodiny ';
        } elseif ($hours < 1 || $hours >=5) {
            $result .= $hours . ' hodin ';
        }

        if ($minutes === 1) {
            $result .= $minutes . ' minuta';
        } elseif ($minutes >=2 && $minutes < 5) {
            $result .= $minutes . ' minuty ';
        } elseif ($minutes < 1 || $minutes >=5) {
            $result .= $minutes . ' minut ';
        }

        return $result;
    }
}
