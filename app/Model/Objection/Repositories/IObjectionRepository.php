<?php

declare(strict_types=1);

namespace Model\Objection\Repositories;

use Model\Objection\Objection;

interface IObjectionRepository
{
    public function saveObjection(Objection $objection) : void;

    /**
     * @return Objection[]
     */
    public function getAllObjections() : array;
}
