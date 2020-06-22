<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Model\Delegate\Delegate;
use Model\Delegate\DelegateAlreadyVoted;
use Model\Delegate\Repositories\IDelegateRepository;
use Model\Vote\Choice;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;
use Model\Vote\VotingResult;
use function assert;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    public function saveUserVote(int $personId, Vote $vote) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($personId, $vote) : void {
            $delegate = $em->getRepository(Delegate::class)->findOneBy(['personId' => $personId]);
            assert($delegate instanceof Delegate);
            if ($delegate->getVotedAt() !== null) {
                throw new DelegateAlreadyVoted();
            }

            $delegate->setVotedAt(new DateTimeImmutable());

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
        return $this->getEntityManager()->transactional(function (EntityManager $em) : VotingResult {
            return new VotingResult(
                $em->getRepository(Vote::class)->count(['choice' => Choice::YES()]),
                $em->getRepository(Vote::class)->count(['choice' => Choice::NO()]),
                $em->getRepository(Vote::class)->count(['choice' => Choice::ABSTAIN()]),
                $em->getRepository(Delegate::class)->count([])
            );
        });
    }
}
