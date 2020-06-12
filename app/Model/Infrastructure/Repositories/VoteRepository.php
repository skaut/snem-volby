<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Model\UsersVote;
use Model\Vote;
use Model\Vote\Repositories\IVoteRepository;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    public function saveUserVote(Vote $vote, UsersVote $usersVote) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($vote, $usersVote) : void {
            $em->persist($vote);
            $em->persist($usersVote);
        });
    }
}
