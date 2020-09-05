<?php

declare(strict_types=1);

namespace Model\Vote;

use Model\Candidate\Candidate;
use Model\DTO\Candidate\SkautisCandidate;
use function array_merge;
use function in_array;
use function ucfirst;

class VotingResult
{
    public const ALREADY_VOTED = 'Nezohledňuje se - už byl/a zvolen/a (místo)náčelníkem';

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

    private int $countOfParticipatedDelegates;

    /** @var array<string, int> */
    private array $functionVotersCount;

    /**
     * @param Candidate[]        $nacelnik
     * @param Candidate[]        $nacelni
     * @param Candidate[]        $nacelnictvo
     * @param Candidate[]        $urkj
     * @param Candidate[]        $rsrj
     * @param array<string, int> $functionVotersCount
     */
    public function __construct(
        array $nacelnik,
        array $nacelni,
        array $nacelnictvo,
        array $urkj,
        array $rsrj,
        int $countOfDelegates,
        int $countOfVotedDelegates,
        int $countOfParticipatedDelegates,
        array $functionVotersCount
    ) {
        $this->nacelnik                     = $nacelnik;
        $this->nacelni                      = $nacelni;
        $this->nacelnictvo                  = $nacelnictvo;
        $this->urkj                         = $urkj;
        $this->rsrj                         = $rsrj;
        $this->countOfDelegates             = $countOfDelegates;
        $this->countOfVotedDelegates        = $countOfVotedDelegates;
        $this->countOfParticipatedDelegates = $countOfParticipatedDelegates;
        $this->functionVotersCount          = $functionVotersCount;
    }

    public function getCountOfVotedDelegates() : int
    {
        return $this->countOfVotedDelegates;
    }

    public function getCountOfParticipatedDelegates() : int
    {
        return $this->countOfParticipatedDelegates;
    }

    /** @return Candidate[] */
    public function getNacelnik() : array
    {
        return $this->evaluate($this->nacelnik, 1);
    }

    /** @return Candidate[] */
    public function getNacelni() : array
    {
        return $this->evaluate($this->nacelni, 1);
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

    /**
     * @param Candidate[] $candidates
     *
     * @return Candidate[]
     */
    private function evaluate(array $candidates, int $limit) : array
    {
        $i   = 1;
        $res = [];
        foreach ($candidates as $c) {
            $c->setVotingResultNote($this->getEvaluateString($c, $i <= $limit));
            $res[] =$c;
            $i++;
        }

        return $res;
    }

    private function getEvaluateString(Candidate $c, bool $isElected) : string
    {
        if ($c->getVotesCount() === 0) {
            return 'Ne' . $c->getElectedWord();
        }

        if ($isElected) {
            return ucfirst($c->getElectedWord());
        }

        return 'Náhradník';
    }

    /** @return Candidate[] */
    private function prepareNacelnictvo(string $sex) : array
    {
        $personIds = $this->getNovyNacelniciIds();
        $res       = [];
        $append    = [];
        $i         = 1;

        foreach ($this->nacelnictvo as $c) {
            if ($c->getSex() !== $sex) {
                continue;
            }

            $c->setVotingResultNote($this->getEvaluateString($c, $i <= 5));

            if (in_array($c->getPersonId(), $personIds)) {
                $c->setVotingResultNote(self::ALREADY_VOTED);
                $append[] = $c;
            } else {
                $res[] = $c;
                $i++;
            }
        }

        return array_merge($res, $append);
    }

    /** @return Candidate[] */
    public function getUrkj() : array
    {
        return $this->evaluate($this->urkj, 7);
    }

    /** @return Candidate[] */
    public function getRsrj() : array
    {
        return $this->evaluate($this->rsrj, 5);
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
        return $this->countOfParticipatedDelegates >= $this->countOfDelegates/2.0;
    }

    public function getFunctionVotersCount(string $functionId) : int
    {
        return $this->functionVotersCount[$functionId];
    }
}
