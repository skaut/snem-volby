<?php

declare(strict_types=1);

namespace Model\Objection;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;
use Model\Delegate\Delegate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Objection extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $text;

    /**
     * @ORM\ManyToOne(targetEntity="\Model\Delegate\Delegate")
     * @ORM\JoinColumn(name="delegate_id", referencedColumnName="id", nullable=false)
     */
    private Delegate $delegate;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function __construct(string $text, Delegate $delegate)
    {
        $this->text      = $text;
        $this->delegate  = $delegate;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getText() : string
    {
        return $this->text;
    }

    public function getDelegate() : Delegate
    {
        return $this->delegate;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }
}
