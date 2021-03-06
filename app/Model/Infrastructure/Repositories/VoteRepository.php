<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use DateTimeImmutable;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Model\Candidate\Candidate;
use Model\Candidate\CandidateFunction;
use Model\Delegate\Delegate;
use Model\Delegate\DelegateAlreadyVoted;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;
use Model\Vote\VotingResult;
use function array_merge;
use function assert;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    /** @param Vote[] $votes */
    public function saveUserVotes(int $personId, array $votes) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($personId, $votes) : void {
            $delegateRepository = $em->getRepository(Delegate::class);
            $delegateId         = $delegateRepository->findOneBy(['personId' => $personId])->getId();
            $delegate           = $delegateRepository->find($delegateId, LockMode::PESSIMISTIC_WRITE);

            assert($delegate instanceof Delegate);
            if ($delegate->getVotedAt() !== null) {
                throw new DelegateAlreadyVoted();
            }

            $delegate->setVotedAt(new DateTimeImmutable());

            foreach ($votes as $vote) {
                assert($vote instanceof Vote);
                $em->persist($vote);
            }
            $em->persist($delegate);
        });
    }

    public function getAllVotesCount() : int
    {
        return $this->getEntityManager()->getRepository(Vote::class)->count([]);
    }

    public function getVotingResult(IDelegateRepository $delegateRepository) : VotingResult
    {
        return $this->getEntityManager()->transactional(function (EntityManager $em) use ($delegateRepository) : VotingResult {
            return new VotingResult(
                $this->getResult($em, CandidateFunction::NACELNIK_ID),
                $this->getResult($em, CandidateFunction::NACELNI_ID),
                $this->getResult($em, CandidateFunction::NACELNICTVO_ID),
                $this->getResult($em, CandidateFunction::URKJ_ID),
                $this->getResult($em, CandidateFunction::RSRJ_ID),
                $em->getRepository(Delegate::class)->count([]),
                $delegateRepository->getVotedCount(),
                $delegateRepository->getParticipatedCount(),
                $this->getFunctionVotersCount($this->getEntityManager())
            );
        });
    }

    /** @return Candidate[] */
    private function getResult(EntityManager $em, string $functionId) : array
    {
        $function = $em->getRepository(CandidateFunction::class)->find($functionId);

        return $em->getRepository(Candidate::class)->createQueryBuilder('c')
            ->select('c')
            ->leftJoin('c.votes', 'v')
            ->where('IDENTITY(c.function) = :function')
            ->groupBy('c')
            ->orderBy('count(v)', 'DESC')
            ->addOrderBy('c.orderCorrection', 'ASC')
            ->setParameter('function', $function)
            ->getQuery()->getResult();
    }

    /** @return array<string, int> */
    private function getFunctionVotersCount(EntityManager $em) : array
    {
        $pairs = $em->getRepository(Candidate::class)->createQueryBuilder('c')
            ->select('IDENTITY(c.function) as function, COUNT(DISTINCT(v.sign)) as count')
            ->leftJoin('c.votes', 'v')
            ->groupBy('c.function')
            ->getQuery()->getResult();

        $nacelnictvo = $em->getRepository(Candidate::class)->createQueryBuilder('c')
            ->select('CONCAT(IDENTITY(c.function), c.sex) as function, COUNT(DISTINCT(v.sign)) as count')
            ->leftJoin('c.votes', 'v')
            ->where('c.function IN (:ids)')
            ->groupBy('c.function')
            ->addGroupBy('c.sex')
            ->setParameter('ids', [CandidateFunction::NACELNICTVO_ID])
            ->getQuery()->getResult();

        $res = [];
        foreach (array_merge($pairs, $nacelnictvo) as $pair) {
            $res[$pair['function']] = (int) $pair['count'];
        }

        return $res;
    }
}
