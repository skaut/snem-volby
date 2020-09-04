<?php

declare(strict_types=1);

namespace Model\Delegate\Repositories;

use Model\Delegate\Delegate;
use Model\Delegate\DelegateNotFound;
use stdClass;

interface IDelegateRepository
{
    /**
     * @param stdClass[] $delegates
     */
    public function saveDelegates(array $delegates) : void;

    /**
     * @throws DelegateNotFound
     */
    public function getDelegate(int $personId) : Delegate;

    public function getCount() : int;

    public function getVotedCount() : int;

    public function getParticipatedCount() : int;

    public function setDelegateFirstLogin(int $personId) : void;
}
