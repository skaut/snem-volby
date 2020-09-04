<?php

declare(strict_types=1);

namespace Model\Delegate\Repositories;

use Doctrine\Common\Collections\Collection;
use Model\Delegate\Delegate;
use Model\Delegate\DelegateNotFound;
use Model\DTO\Delegate\SkautisDelegate;

interface IDelegateRepository
{
    /**
     * @param SkautisDelegate[] $delegates
     */
    public function saveDelegates(array $delegates) : void;

    /**
     * @return Collection<int, Delegate>
     */
    public function getDelegates() : Collection;

    /**
     * @throws DelegateNotFound
     */
    public function getDelegate(int $personId) : Delegate;

    public function getCount() : int;

    public function getVotedCount() : int;

    public function getParticipatedCount() : int;

    public function setDelegateFirstLogin(int $personId) : void;
}
