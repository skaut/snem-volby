<?php

declare(strict_types=1);

namespace Model\Infrastructure\Repositories;

use Doctrine\ORM\EntityManager;
use eGen\MessageBus\Bus\EventBus;
use IntegrationTest;
use Model\Candidate\Candidate;
use Model\DTO\Candidate\SkautisCandidate;

class CandidateRepositoryTest extends IntegrationTest
{
    private const TABLE          = 'candidate';
    private const TABLE_FUNCTION = 'candidate_function';

    private CandidateRepository $repository;

    public function _before() : void
    {
        parent::_before();
        $this->repository = new CandidateRepository(
            $this->tester->grabService(EntityManager::class),
            $this->tester->grabService(EventBus::class)
        );
    }

    /**
     * @return string[]
     */
    public function getTestedAggregateRoots() : array
    {
        return [Candidate::class];
    }

    public function testSaveCandidates() : void
    {
        $this->tester->haveInDatabase(self::TABLE_FUNCTION, [
            'id' => 25,
            'label' => 'člen RSRJ',
            'max_count' => 5,
            'show' => 1,
            'order' => 50,
        ]);

        $candidate = new SkautisCandidate(1024, 123, SkautisCandidate::SEX_FEMALE, 'Anna z RSRJ', 25, null);

        $this->repository->saveCandidates([$candidate]);

        $this->tester->seeInDatabase(self::TABLE, [
            'id' => $candidate->getId(),
            'function_id' => $candidate->getFunctionId(),
            'running_mate' => $candidate->getRunningMateId(),
            'person_id' => $candidate->getPersonId(),
            'name' => $candidate->getName(),
        ]);
    }

    public function testSaveCandidatesPair() : void
    {
        $this->tester->haveInDatabase(self::TABLE_FUNCTION, [
            'id' => 10,
            'label' => 'náčelník',
            'max_count' => 1,
            'show' => 1,
            'order' => 10,
        ]);

        $this->tester->haveInDatabase(self::TABLE_FUNCTION, [
            'id' => 13,
            'label' => 'místonáčelník',
            'max_count' => 1,
            'show' => 0,
            'order' => 100,
        ]);

        $nacelnik      = new SkautisCandidate(1027, 234, SkautisCandidate::SEX_MALE, 'Karel Náčelník', 10, 2038);
        $mistonacelnik = new SkautisCandidate(2038, 345, SkautisCandidate::SEX_MALE, 'Joe Místonáčelní', 13, null);

        $this->repository->saveCandidates([$nacelnik, $mistonacelnik]);

        $this->tester->seeInDatabase(self::TABLE, [
            'id' => $nacelnik->getId(),
            'function_id' => $nacelnik->getFunctionId(),
            'running_mate' => $nacelnik->getRunningMateId(),
            'person_id' => $nacelnik->getPersonId(),
            'name' => $nacelnik->getName(),
        ]);
    }
}
