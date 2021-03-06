<?php

declare(strict_types=1);

namespace Model\Vote;

use Doctrine\ORM\Mapping as ORM;
use Model\Candidate\Candidate;
use Model\Common\Aggregate;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Vote extends Aggregate
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="vote_id")
     *
     * @var VoteId
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Model\Candidate\Candidate", inversedBy="votes")
     * @ORM\JoinColumn(name="candidate_id", referencedColumnName="id", nullable=false)
     */
    private Candidate $candidate;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $sign;

    public function __construct(string $sign, Candidate $candidate)
    {
        $this->id        = VoteId::generate();
        $this->sign      = $sign;
        $this->candidate = $candidate;
    }
}
