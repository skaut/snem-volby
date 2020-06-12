<?php

declare(strict_types=1);

namespace Model\User;

class SkautisRole
{
    private string $key;

    private string $name;

    private int $unitId;

    private string $unitName;

    public function __construct(
        string $key,
        string $name,
        int $unitId,
        string $unitName
    ) {
        $this->key      = $key;
        $this->name     = $name;
        $this->unitId   = $unitId;
        $this->unitName = $unitName;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getUnitId() : int
    {
        return $this->unitId;
    }

    public function getUnitName() : string
    {
        return $this->unitName;
    }
}
