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

    public function __construct(int $personId)
    {
        $this->personId     = $personId;
        $this->createdAt    = new DateTimeImmutable();
        $this->firstLoginAt = null;
        $this->votedAt      = null;
    }

    public function getId() : int
    {
        return $this->id;
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
}
