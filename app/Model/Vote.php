<?php

declare(strict_types=1);

namespace Model;

use Consistence\Doctrine\Enum\EnumAnnotation;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;
use Model\Vote\Option;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Vote extends Aggregate
{
    public const VOTING_BEGIN_AT = '2020-06-25 09:00:00';
    public const VOTING_END_AT   = '2020-06-29 23:59:00';

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
     * @var Option
     * @EnumAnnotation(class=Option::class)
     */
    private $option;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @var DateTimeImmutable
     */
    private $createdAt;

    public function __construct(Option $option)
    {
        $this->option    = $option;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getOption() : Option
    {
        return $this->option;
    }
}
