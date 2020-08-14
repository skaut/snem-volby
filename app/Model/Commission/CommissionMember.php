<?php

declare(strict_types=1);

namespace Model\Commission;

use Doctrine\ORM\Mapping as ORM;
use Model\Common\Aggregate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class CommissionMember extends Aggregate
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

    public function __construct(int $personId)
    {
        $this->personId = $personId;
    }

    public function getId() : int
    {
        return $this->id;
    }
}
