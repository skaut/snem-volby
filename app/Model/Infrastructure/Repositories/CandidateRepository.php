<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Model\Candidate\Candidate;
use Model\Candidate\CandidateFunction;
use Model\Candidate\CandidateNotFound;
use Model\Candidate\FunctionNotFound;
use Model\Candidate\Repositories\ICandidateRepository;
use Model\DTO\Candidate\SkautisCandidate;
use function assert;
use function count;

final class CandidateRepository extends AggregateRepository implements ICandidateRepository
{
    /**
     * @param SkautisCandidate[] $candidates
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveCandidates(array $candidates) : void
    {
        $fillRunningMates = [];
        $candidateObjs    = [];
        foreach ($candidates as $candidate) {
            assert($candidate instanceof SkautisCandidate);
            $candidateObjs[$candidate->getId()] = new Candidate(
                $candidate->getId(),
                $candidate->getPersonId(),
                $candidate->getSex(),
                $candidate->getName(),
                $this->getFunction($candidate->getFunctionId())
            );
            if (! $candidate->hasRunningMate()) {
                continue;
            }

            $fillRunningMates[$candidate->getId()] = $candidate->getRunningMateId();
        }
        // set relationship for candidate pairs
        foreach ($fillRunningMates as $primaryId => $secondaryId) {
            $candidateObjs[$primaryId]->setRunningMate($candidateObjs[$secondaryId]);
        }

        $this->getEntityManager()->transactional(function (EntityManager $em) use ($candidateObjs) : void {
            foreach ($candidateObjs as $candidateObj) {
                $em->persist($candidateObj);
            }
            $em->flush();
        });
    }

    public function getCount() : int
    {
        return $this->getEntityManager()->getRepository(Candidate::class)->count([]);
    }

    /** @return  CandidateFunction[] */
    public function getAllFunctions() : array
    {
        $res = $this->getEntityManager()->getRepository(CandidateFunction::class)->findAll();
        if (count($res) === 0) {
            return [];
        }

        return $res;
    }

    /** @return array<int, array<int, Candidate>> */
    public function getCandidatesByFunction() : array
    {
        $byFunction = [];
        $candidates = $this->getEntityManager()->getRepository(Candidate::class)->findBy([], ['name' => Criteria::ASC]);
        foreach ($candidates as $candidate) {
            assert($candidate instanceof Candidate);
            $byFunction[$candidate->getFunction()->getId()][$candidate->getId()] = $candidate;
        }

        return $byFunction;
    }

    /**
     * @return string[][]
     */
    public function getFunctionCandidatesCounts() : array
    {
        $qb = $this->getEntityManager()->getRepository(CandidateFunction::class)->createQueryBuilder('f');

        return $qb
            ->select('f.label, count(c) as count')
            ->leftJoin('f.candidates', 'c')
            ->groupBy('f')
            ->orderBy('f.order')
            ->getQuery()
            ->getResult();
    }

    private function getFunction(int $functionId) : CandidateFunction
    {
        $function = $this->getEntityManager()->getRepository(CandidateFunction::class)->find($functionId);

        if ($function === null) {
            throw new FunctionNotFound();
        }

        return $function;
    }

    public function getCandidate(int $id) : Candidate
    {
        $candidate = $this->getEntityManager()->getRepository(Candidate::class)->findOneBy(['id' => $id]);

        if ($candidate === null) {
            throw new CandidateNotFound();
        }

        return $candidate;
    }

    public function swapCandidates(int $candidateUpId, int $candidateDownId): void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($candidateUpId, $candidateDownId) : void {
            $candidateUp = $this->getCandidate($candidateUpId);
            $candidateDown = $this->getCandidate($candidateDownId);

            $candidateDownCorrection = $candidateDown->getOrderCorrection();
            $candidateUpCorrection = $candidateUp->getOrderCorrection();

            $candidateUp->setOrderCorrection($candidateDownCorrection);
            $candidateDown->setOrderCorrection($candidateUpCorrection);

            $em->persist($candidateUp);
            $em->persist($candidateDown);
            $em->flush();
        });
    }
}
