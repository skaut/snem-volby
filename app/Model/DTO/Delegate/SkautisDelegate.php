<?php

declare(strict_types=1);

namespace Model\DTO\Delegate;

class SkautisDelegate
{
    private int $personId;
    private string $name;
    private string $type;
    private ?string $unitNumber;
    private ?string $unitName;

    public function __construct(int $personId, string $name, string $type, ?string $unitNumber, ?string $unitName)
    {
        $this->personId   = $personId;
        $this->name       = $name;
        $this->type       = $type;
        $this->unitNumber = $unitNumber;
        $this->unitName   = $unitName;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getUnitNumber() : ?string
    {
        return $this->unitNumber;
    }

    public function getUnitName() : ?string
    {
        return $this->unitName;
    }
}
