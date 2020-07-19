<?php

declare(strict_types=1);

namespace Model\Candidate;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Candidate
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $personId;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="CandidateFunction")
     * @ORM\JoinColumn(name="function_id", referencedColumnName="id", nullable=false)
     */
    private CandidateFunction $function;

    /**
     * @ORM\OneToOne(targetEntity="Candidate")
     * @ORM\JoinColumn(name="candidate_with", referencedColumnName="id")
     */
    private ?self $candidateWith;

    public function __construct(int $id, int $personId, string $name, CandidateFunction $function)
    {
        $this->id       = $id;
        $this->personId = $personId;
        $this->name     = $name;
        $this->function = $function;
    }

    public function setCandidateWith(self $candidateWith) : void
    {
        $this->candidateWith = $candidateWith;
    }
}
