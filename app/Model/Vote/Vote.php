<?php

declare(strict_types=1);

namespace Model\Vote;

use Consistence\Doctrine\Enum\EnumAnnotation;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Vote extends Aggregate
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
     * @ORM\Column(type="string_enum")
     *
     * @var Choice
     * @EnumAnnotation(class=Choice::class)
     */
    private $choice;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @var DateTimeImmutable
     */
    private $createdAt;

    public function __construct(Choice $choice)
    {
        $this->choice    = $choice;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getChoice() : Choice
    {
        return $this->choice;
    }
}
