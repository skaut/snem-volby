<?php

declare(strict_types=1);

namespace Model\Config\Repositories;

use DateTimeImmutable;
use Model\Config\Item;

interface IConfigRepository
{
    public function setValue(Item $item, ?string $value) : void;

    public function setDateTimeValue(Item $item, ?DateTimeImmutable $value) : void;

    public function getValue(Item $item) : ?string;

    public function getDateTimeValue(Item $item) : ?DateTimeImmutable;
}
