<?php

declare(strict_types=1);

namespace Model\Candidate;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class CandidateFunction
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private string $label;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $maxCount;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $show;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $order;

    /**
     * @ORM\OneToMany(targetEntity="Candidate", mappedBy="function")
     * @ORM\JoinColumn(name="id", referencedColumnName="function_id")
     * @var Candidate[]|Collection
     */
    private Collection $candidates;
}
