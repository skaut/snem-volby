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
    public const NACELNI_ID       = '11';
    public const MISTONACELNI_ID  = '14';
    public const NACELNIK_ID      = '10';
    public const MISTONACELNIK_ID = '13';
    public const URKJ_ID          = '23';
    public const RSRJ_ID          = '25';
    public const NACELNICTVO_ID   = '106';

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
     *
     * @var Collection<int, Candidate>
     */
    private Collection $candidates;

    public function getId() : int
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getMaxCount() : int
    {
        return $this->maxCount;
    }

    public function isShow() : bool
    {
        return $this->show;
    }
}
