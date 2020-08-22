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
     * @ORM\Column(type="string", length=10)
     */
    private string $sex;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="CandidateFunction", inversedBy="candidates")
     * @ORM\JoinColumn(name="function_id", referencedColumnName="id", nullable=false)
     */
    private CandidateFunction $function;

    /**
     * @ORM\OneToOne(targetEntity="Candidate")
     * @ORM\JoinColumn(name="running_mate", referencedColumnName="id")
     */
    private ?self $runningMate;

    public function __construct(int $id, int $personId, string $sex, string $name, CandidateFunction $function)
    {
        $this->id       = $id;
        $this->personId = $personId;
        $this->sex      = $sex;
        $this->name     = $name;
        $this->function = $function;
    }

    public function setRunningMate(self $runningMate) : void
    {
        $this->runningMate = $runningMate;
    }

    public function getSex() : string
    {
        return $this->sex;
    }
}
