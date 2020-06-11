<?php

declare(strict_types=1);

namespace Model\User\ReadModel\QueryHandlers;

use Model\Common\UnitId;
use Model\User\Exception\UserHasNoUnit;
use Skautis\Skautis;

final class UserUnitIdQueryHandler
{
    private Skautis $skautis;

    public function __construct(Skautis $skautis)
    {
        $this->skautis = $skautis;
    }

    /**
     * @throws UserHasNoUnit
     */
    public function __invoke() : UnitId
    {
        $user   = $this->skautis->getUser();
        $unitId = $user->getUnitId();

        if ($unitId === null || $unitId === 0) {
            throw UserHasNoUnit::fromLoginId($user->getLoginId());
        }

        return UnitId::fromInt($unitId);
    }
}
