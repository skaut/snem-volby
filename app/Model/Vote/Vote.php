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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string_enum")
     *
     * @EnumAnnotation(class=Choice::class)
     */
    private Choice $choice;

    public function __construct(Choice $choice)
    {
        $this->choice    = $choice;
    }

    public function getChoice() : Choice
    {
        return $this->choice;
    }
}
