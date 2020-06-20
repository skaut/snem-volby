<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use DateTimeImmutable;
use Model\Config\Config;
use Model\Config\Item;
use Model\Config\Repositories\IConfigRepository;

final class ConfigRepository extends AggregateRepository implements IConfigRepository
{
    public function setValue(Item $item, ?string $value) : void
    {
        $config = $this->getEntityManager()->getRepository(Config::class)->findOneBy(['item' => $item]);
        $config->setValue($value);
        $this->getEntityManager()->persist($config);
        $this->getEntityManager()->flush($config);
    }

    public function setDateTimeValue(Item $item, ?DateTimeImmutable $value) : void
    {
        $this->setValue($item, $value !== null ? $value->format(DateTimeImmutable::ISO8601) : null);
    }

    public function getValue(Item $item) : ?string
    {
        return $this->getEntityManager()->getRepository(Config::class)->findOneBy(['item' => $item])->getValue();
    }

    public function getDateTimeValue(Item $item) : ?DateTimeImmutable
    {
        $value = $this->getValue($item);

        return $value !== null ? new DateTimeImmutable($value) : null;
    }
}
