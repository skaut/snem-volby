<?php

declare(strict_types=1);

namespace Model\User\Commands;

use Model\User\Handlers\SaveFirstLoginHandler;

/**
 * @see SaveFirstLoginHandler
 */
final class SaveFirstLogin
{
    private int $personId;

    public function __construct(int $personId)
    {
        $this->personId = $personId;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }
}
