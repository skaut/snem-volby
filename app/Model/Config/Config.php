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
class Config extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string_enum")
     *
     * @var ConfigItem
     * @EnumAnnotation(class=ConfigItem::class)
     */
    private $item;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $value;

    public function getValue() : ?string
    {
        return $this->value;
    }

    public function setValue(?string $value) : void
    {
        $this->value = $value;
    }
}
