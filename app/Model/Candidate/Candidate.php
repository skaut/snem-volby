<?php

declare(strict_types=1);

namespace Model\Candidate;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Model\DTO\Candidate\SkautisCandidate;
use Model\Vote\Vote;

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

    /**
     * @ORM\OneToMany(targetEntity="\Model\Vote\Vote", mappedBy="candidate")
     *
     * @var Collection<int, Vote>
     */
    private Collection $votes;

    private string $votingResultNote = '';

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

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFunction() : CandidateFunction
    {
        return $this->function;
    }

    public function getRunningMate() : ?self
    {
        return $this->runningMate;
    }

    public function getPersonId() : int
    {
        return $this->personId;
    }

    public function getSex() : string
    {
        return $this->sex;
    }

    public function getVotesCount() : int
    {
        return $this->votes->count();
    }

    public function getDisplayName() : string
    {
        return $this->getName() . ($this->getRunningMate() === null ? '' : ' a ' . $this->getRunningMate()->getName());
    }

    public function setVotingResultNote(string $note) : void
    {
        $this->votingResultNote = $note;
    }

    public function getVotingResultNote() : string
    {
        return $this->votingResultNote;
    }

    public function getElectedWord() : string
    {
        return $this->getSex() === SkautisCandidate::SEX_FEMALE ? 'zvolena' : 'zvolen';
    }
}
