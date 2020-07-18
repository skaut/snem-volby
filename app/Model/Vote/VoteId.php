<?php

declare(strict_types=1);

namespace Model\Vote;

use InvalidArgumentException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function sprintf;

final class VoteId
{
    private string $id;

    /**
     * @throws InvalidUuidStringException
     * @throws InvalidArgumentException
     */
    private function __construct(UuidInterface $uuid)
    {
        if ($uuid->getVersion() !== 4) {
            throw new InvalidArgumentException(
                sprintf('Invalid id "%s", valid ID is only UUIDv4', $uuid->toString())
            );
        }

        $this->id = $uuid->toString(); // valid UUID
    }

    public static function generate() : self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $id) : self
    {
        return new self(Uuid::fromString($id));
    }

    public function toString() : string
    {
        return $this->id;
    }

    public function __toString() : string
    {
        return $this->toString();
    }
}
