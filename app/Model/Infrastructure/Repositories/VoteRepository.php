<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use Model\Vote\Repositories\IVoteRepository;
use Model\Vote\UsersVote;
use Model\Vote\Vote;

final class VoteRepository extends AggregateRepository implements IVoteRepository
{
    public function saveUserVote(Vote $vote, UsersVote $usersVote) : void
    {
        $this->getEntityManager()->transactional(function (EntityManager $em) use ($vote, $usersVote) : void {
            $em->persist($vote);
            $em->persist($usersVote);
        });
    }

    public function getUserVote(int $personId) : ?UsersVote
    {
        return $this->getEntityManager()->getRepository(UsersVote::class)->findOneBy([
            'personId' => $personId
        ]);
    }
}
