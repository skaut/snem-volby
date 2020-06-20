<?php

declare(strict_types=1);

namespace Model\Delegate\Repositories;

use Model\Delegate\Delegate;

interface IDelegateRepository
{
    public function saveDelegate(Delegate $delegate) : void;

    public function getDelegate(int $personId) : ?Delegate;

    public function getCount() : int;
}
