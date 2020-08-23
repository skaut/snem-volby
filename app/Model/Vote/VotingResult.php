<?php

declare(strict_types=1);

namespace Model\Vote;

use Model\Candidate\Candidate;
use Model\DTO\Candidate\SkautisCandidate;
use function array_merge;
use function in_array;

class VotingResult
{
    /** @var Candidate[] */
    private array $nacelnik;

    /** @var Candidate[] */
    private array $nacelni;

    /** @var Candidate[] */
    private array $nacelnictvo;

    /** @var Candidate[] */
    private array $urkj;

    /** @var Candidate[] */
    private array $rsrj;

    private int $countOfDelegates;

    private int $countOfVotedDelegates;

    /**
     * @param Candidate[] $nacelnik
     * @param Candidate[] $nacelni
     * @param Candidate[] $nacelnictvo
     * @param Candidate[] $urkj
     * @param Candidate[] $rsrj
     */
    public function __construct(
        array $nacelnik,
        array $nacelni,
        array $nacelnictvo,
        array $urkj,
        array $rsrj,
        int $countOfDelegates,
        int $countOfVotedDelegates
    ) {
        $this->nacelnik              = $nacelnik;
        $this->nacelni               = $nacelni;
        $this->nacelnictvo           = $nacelnictvo;
        $this->urkj                  = $urkj;
        $this->rsrj                  = $rsrj;
        $this->countOfDelegates      = $countOfDelegates;
        $this->countOfVotedDelegates = $countOfVotedDelegates;
    }

    public function getCountOfVotedDelegates() : int
    {
        return $this->countOfVotedDelegates;
    }

    /** @return Candidate[] */
    public function getNacelnik() : array
    {
        return $this->nacelnik;
    }

    /** @return Candidate[] */
    public function getNacelni() : array
    {
        return $this->nacelni;
    }

    /** @return Candidate[] */
    public function getNacelnictvoMale() : array
    {
        return $this->prepareNacelnictvo(SkautisCandidate::SEX_MALE);
    }

    /** @return Candidate[] */
    public function getNacelnictvoFemale() : array
    {
        return $this->prepareNacelnictvo(SkautisCandidate::SEX_FEMALE);
    }

    /** @return Candidate[] */
    private function prepareNacelnictvo(string $sex) : array
    {
        $personIds = $this->getNovyNacelniciIds();
        $res       = [];
        $append    = [];
        foreach ($this->nacelnictvo as $i => $n) {
            if ($n->getSex() !== $sex) {
                continue;
            }

            if (in_array($n->getPersonId(), $personIds)) {
                $n->setVotingResultNote($n->getSex() === SkautisCandidate::SEX_FEMALE ? 'Zvolena (místo)náčelní' : 'Zvolen (místo)náčelníkem');
                $append[] = $n;
            } else {
                $res[] = $n;
            }
        }

        return array_merge($res, $append);
    }

    /** @return Candidate[] */
    public function getUrkj() : array
    {
        return $this->urkj;
    }

    /** @return Candidate[] */
    public function getRsrj() : array
    {
        return $this->rsrj;
    }

    public function getCountOfDelegates() : int
    {
        return $this->countOfDelegates;
    }

    /** @return int[] */
    public function getNovyNacelniciIds() : array
    {
        $nacelni  = $this->getNacelni()[0];
        $nacelnik = $this->getNacelnik()[0];

        return [
            $nacelni->getPersonId(),
            $nacelni->getRunningMate()->getPersonId(),
            $nacelnik->getPersonId(),
            $nacelnik->getRunningMate()->getPersonId(),
        ];
    }

    public function isQuorumSatisfied() : bool
    {
        return $this->countOfVotedDelegates >= $this->countOfDelegates/2.0;
    }

//    public function getMinVotes() : int
//    {
//        return (int) ceil($this->totalCountOfDelegates * (3/5));
//    }
}
