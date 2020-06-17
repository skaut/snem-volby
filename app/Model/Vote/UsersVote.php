<?php

declare(strict_types=1);

namespace Model\Vote;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class UsersVote extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var      int
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     *
     * @var int
     */
    private $personId;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @var DateTimeImmutable
     */
    private $createdAt;

    public function __construct(int $personId)
    {
        $this->personId  = $personId;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }
}
