<?php

declare(strict_types=1);

namespace Model\Delegate;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Delegate extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private int $personId;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string")
     */
    private string $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $unitNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $unitName;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $firstLoginAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $votedAt;

    public function __construct(int $personId, string $name, string $type, ?string $unitNumber, ?string $unitName)
    {
        $this->personId     = $personId;
        $this->name         = $name;
        $this->type         = $type;
        $this->unitNumber   = $unitNumber;
        $this->unitName     = $unitName;
        $this->createdAt    = new DateTimeImmutable();
        $this->firstLoginAt = null;
        $this->votedAt      = null;
    }

    public function getId() : int
    {
        return $this->id;
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

    public function getVotedAt() : ?DateTimeImmutable
    {
        return $this->votedAt;
    }

    public function setVotedAt(DateTimeImmutable $votedAt) : void
    {
        $this->votedAt = $votedAt;
    }

    public function setFirstLoginAt() : bool
    {
        if ($this->firstLoginAt === null) {
            $this->firstLoginAt = new DateTimeImmutable();

            return true;
        }

        return false;
    }

    public function isVoted() : bool
    {
        return $this->votedAt !== null;
    }

    public function isParticipated() : bool
    {
        return $this->firstLoginAt !== null;
    }
}
