<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Model\Candidate\Candidate;
use Model\Candidate\CandidateFunction;
use Model\Candidate\FunctionNotFound;
use Model\Candidate\Repositories\ICandidateRepository;
use stdClass;
use function array_column;

final class CandidateRepository extends AggregateRepository implements ICandidateRepository
{
    /**
     * @param stdClass[] $candidates
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveCandidates(array $candidates) : void
    {
        // index array by ID
        $candidates = array_column($candidates, null, 'ID');

        $fillCandidateWith = [];
        $candidateObjs     = [];
        foreach ($candidates as $candidate) {
            $candidateObjs[$candidate->ID] = new Candidate($candidate->ID, $candidate->ID_Person, $candidate->Person, $this->getFunction($candidate->ID_FunctionType));
            if (! isset($candidate->ID_CandidateWith) || $candidate->ID_CandidateWith === null) {
                continue;
            }

            $fillCandidateWith[$candidate->ID] = $candidate->ID_CandidateWith;
        }
        // set relationship for candidate pairs
        foreach ($fillCandidateWith as $primaryId => $secondaryId) {
            $candidateObjs[$primaryId]->setCandidateWith($candidateObjs[$secondaryId]);
        }

        $this->getEntityManager()->transactional(function (EntityManager $em) use ($candidateObjs) : void {
            foreach ($candidateObjs as $candidateObj) {
                $em->persist($candidateObj);
            }
            $this->getEntityManager()->flush();
        });
    }

    public function getCount() : int
    {
        return $this->getEntityManager()->getRepository(Candidate::class)->count([]);
    }

    private function getFunction(int $functionId) : CandidateFunction
    {
        $function = $this->getEntityManager()->getRepository(CandidateFunction::class)->find($functionId);

        if ($function === null) {
            throw new FunctionNotFound();
        }

        return $function;
    }
}
