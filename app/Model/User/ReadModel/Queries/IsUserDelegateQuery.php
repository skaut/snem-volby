<?php

declare(strict_types=1);

namespace Model\User\ReadModel\Queries;

final class IsUserDelegateQuery
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
