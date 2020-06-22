<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Model\Delegate\Delegate;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Choice;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;
use Model\Vote\VotingResult;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    public function saveUserVote(Vote $vote, Delegate $delegate) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($vote, $delegate) : void {
            $em->persist($vote);
            $em->persist($delegate);
        });
    }

    public function getAllVotesCount() : int
    {
        return $this->getEntityManager()->getRepository(Vote::class)->count([]);
    }

    public function getVotingResult(IDelegateRepository $delegateRepository) : VotingResult
    {
        $this->getEntityManager()->beginTransaction();

        return new VotingResult(
            $this->getEntityManager()->getRepository(Vote::class)->count(['choice' => Choice::YES()]),
            $this->getEntityManager()->getRepository(Vote::class)->count(['choice' => Choice::NO()]),
            $this->getEntityManager()->getRepository(Vote::class)->count(['choice' => Choice::ABSTAIN()]),
            $delegateRepository->getCount()
        );

        $this->getEntityManager()->commit();
    }
}
