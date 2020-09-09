<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Model\Objection\Objection;
use Model\Objection\Repositories\IObjectionRepository;
use function count;

final class ObjectionRepository extends AggregateRepository implements IObjectionRepository
{
    public function saveObjection(Objection $objection) : void
    {
        $this->getEntityManager()->persist($objection);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Objection[]
     */
    public function getAllObjections() : array
    {
        $res = $this->getEntityManager()->getRepository(Objection::class)->findAll();
        if (count($res) === 0) {
            return [];
        }

        return $res;
    }
}
