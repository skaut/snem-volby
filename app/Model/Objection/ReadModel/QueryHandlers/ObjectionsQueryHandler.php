<?php

declare(strict_types=1);

namespace Model\Objection\ReadModel\QueryHandlers;

use Model\Objection\Objection;
use Model\Objection\ReadModel\Queries\ObjectionsQuery;
use Model\Objection\Repositories\IObjectionRepository;

class ObjectionsQueryHandler
{
    private IObjectionRepository $objectionRepository;

    public function __construct(IObjectionRepository $objectionRepository)
    {
        $this->objectionRepository = $objectionRepository;
    }

    /**
     * @return Objection[]
     */
    public function __invoke(ObjectionsQuery $query) : array
    {
        return $this->objectionRepository->getAllObjections();
    }
}
