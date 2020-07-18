<?php

declare(strict_types=1);

namespace Model\Vote;

use Consistence\Doctrine\Enum\EnumAnnotation;
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
     * @ORM\Column(type="vote_id")
     *
     * @var VoteId
     */
    private $id;

    /**
     * @ORM\Column(type="string_enum")
     *
     * @EnumAnnotation(class=Choice::class)
     */
    private Choice $choice;

    public function __construct(Choice $choice)
    {
        $this->choice = $choice;
        $this->id     = VoteId::generate();
    }

    public function getChoice() : Choice
    {
        return $this->choice;
    }
}
