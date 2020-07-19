<?php

declare(strict_types=1);

namespace Model\Candidate\Handlers;

use eGen\MessageBus\Bus\QueryBus;
use Model\Candidate\Commands\SaveCandidates;
use Model\Candidate\ReadModel\Queries\CandidatesCountQuery;
use Model\Candidate\Repositories\ICandidateRepository;
use Model\DTO\Cashbook\SkautisCandidate;
use Skautis\Skautis;

final class SaveCandidatesHandler
{
    private ICandidateRepository $candidateRepository;

    private Skautis $skautis;
    private QueryBus $queryBus;
    private int $congressEventId;

    public function __construct(
        int $congressEventId,
        ICandidateRepository $candidateRepository,
        Skautis $skautis,
        QueryBus $queryBus
    ) {
        $this->candidateRepository = $candidateRepository;
        $this->skautis             = $skautis;
        $this->queryBus            = $queryBus;
        $this->congressEventId     = $congressEventId;
    }

    public function __invoke(SaveCandidates $command) : void
    {
        if ($this->queryBus->handle(new CandidatesCountQuery()) !== 0) {
            return;
        }
        $skautisCandidates = $this->skautis->events->CandidateAllApproved(['ID_EventCongress' => $this->congressEventId]);

        $res = [];
        foreach ($skautisCandidates as $c) {
            $res[$c->ID] = new SkautisCandidate(
                $c->ID,
                $c->ID_Person,
                $c->Person,
                $c->ID_FunctionType,
                $c->ID_CandidateWith
            );
        }
        $this->candidateRepository->saveCandidates($res);
    }
}
