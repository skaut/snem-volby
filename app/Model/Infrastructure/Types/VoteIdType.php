<?php

declare(strict_types=1);

namespace Model\Infrastructure\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use LogicException;
use Model\Vote\VoteId;
use function gettype;
use function is_string;
use function sprintf;

final class VoteIdType extends GuidType
{
    public function getName() : string
    {
        return 'vote_id';
    }

    /**
     * @param mixed $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?VoteId
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value)) {
            throw new LogicException(sprintf("It has to be 'string'! '%s' given.", gettype($value)));
        }

        return VoteId::fromString($value);
    }

    /**
     * @param mixed $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value === null) {
            return null;
        }
        if (! $value instanceof VoteId) {
            throw new LogicException(sprintf("It has to be instance of 'VoteId'! '%s' given.", gettype($value)));
        }

        return $value->toString();
    }
}
