<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Model\Delegate\Delegate;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\Vote;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    public function saveUserVote(Vote $vote, Delegate $delegate) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($vote, $delegate) : void {
            $em->persist($vote);
            $em->persist($delegate);
        });
    }
}
