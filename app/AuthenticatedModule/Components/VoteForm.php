<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Forms\BaseForm;
use eGen\MessageBus\Bus\CommandBus;
use eGen\MessageBus\Bus\QueryBus;
use Model\Candidate\Candidate;
use Model\Candidate\CandidateFunction;
use Model\Candidate\ReadModel\Queries\CandidateFunctionListQuery;
use Model\Candidate\ReadModel\Queries\CandidatesListByFunctionQuery;
use Model\Config\ReadModel\Queries\VotingPublishedQuery;
use Model\Config\ReadModel\Queries\VotingTimeQuery;
use Model\Delegate\DelegateAlreadyVoted;
use Model\Delegate\ReadModel\Queries\DelegateVoteTimeQuery;
use Model\DTO\Candidate\SkautisCandidate;
use Model\UserService;
use Model\Vote\Commands\SaveVotes;
use Model\Vote\Vote;
use Nette\Application\UI\Form;
use Ramsey\Uuid\Uuid;
use function array_key_exists;
use function array_map;
use function array_merge;
use function array_values;
use function assert;

final class VoteForm extends BaseControl
{
    public const NO_VOTE_ID = 0;

    private CommandBus $commandBus;

    private QueryBus $queryBus;

    private UserService $userService;

    private bool $isUserDelegate;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UserService $userService
    ) {
        $this->commandBus     = $commandBus;
        $this->queryBus       = $queryBus;
        $this->userService    = $userService;
        $this->isUserDelegate = $this->userService->isDelegate();
    }

    public function render() : void
    {
        $personId = $this->userService->getUserDetail()->ID_Person;

        $this->template->setFile(__DIR__ . '/templates/VoteForm.latte');
        $this->template->setParameters([
            'isUserDelegate'    => $this->isUserDelegate,
            'functions' => $this->queryBus->handle(new CandidateFunctionListQuery()),
        ]);

        if ($this->isUserDelegate) {
            $this->template->setParameters([
                'userVoteTime' => $this->queryBus->handle(new DelegateVoteTimeQuery($personId)),
                'isResultPublished' => $this->queryBus->handle(new VotingPublishedQuery()) !== null,
                'votingTime'   => $this->queryBus->handle(new VotingTimeQuery()),
            ]);
        }

        $this->template->render();
    }

    protected function createComponentForm() : BaseForm
    {
        $form                 = new BaseForm();
        $candidatesByFunction = $this->queryBus->handle(new CandidatesListByFunctionQuery());

        $nacelnictvoFemale = [];
        $nacelnictvoMale   = [];
        foreach ($candidatesByFunction[CandidateFunction::NACELNICTVO_ID] as $c) {
            assert($c instanceof Candidate);
            if ($c->getSex() === SkautisCandidate::SEX_MALE) {
                $nacelnictvoMale[$c->getId()] = $c;
            } else {
                $nacelnictvoFemale[$c->getId()] = $c;
            }
        }

        $form->addCheckboxList(CandidateFunction::NACELNI_ID, 'Náčelní a místonáčelní', $this->prepareItems($candidatesByFunction, (int) CandidateFunction::NACELNI_ID))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maximálně %d dvojici náčelník a místonáčelní', 1);

        $form->addCheckboxList(CandidateFunction::NACELNIK_ID, 'Náčelník a místonáčelník', $this->prepareItems($candidatesByFunction, (int) CandidateFunction::NACELNIK_ID))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maximálně %d dvojici náčelníka a místonáčelníka', 1);

        $form->addCheckboxList(CandidateFunction::NACELNICTVO_ID . 'female', 'Členek náčelnictva (max 5)', array_map(function (Candidate $c) {
            return $c->getName();
        }, $nacelnictvoFemale))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maxiálně %d členek náčelnictva', 5);

        $form->addCheckboxList(CandidateFunction::NACELNICTVO_ID . 'male', 'Členek náčelnictva (max 5)', array_map(function (Candidate $c) {
            return $c->getName();
        }, $nacelnictvoMale))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maxiálně %d členů náčelnictva', 5);

        $form->addCheckboxList(CandidateFunction::URKJ_ID, 'Člen ústřední revizní komise', $this->prepareItems($candidatesByFunction, (int) CandidateFunction::URKJ_ID))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maxiálně %d členů RSRJ', 7);

        $form->addCheckboxList(CandidateFunction::RSRJ_ID, 'Člen rozhodčí a smírčí rady', $this->prepareItems($candidatesByFunction, (int) CandidateFunction::RSRJ_ID))
            ->addRule(Form::MAX_LENGTH, 'Zvolit lze maxiálně %d členů RSRJ', 5);

        $form->addSubmit('save');

        $form->onSuccess[] = function (Form $form) : void {
            $values = $form->getValues();

            $candidatesByFunction = $this->queryBus->handle(new CandidatesListByFunctionQuery());

            $votes = [];
            $sign  = Uuid::uuid4()->toString();
            if (! empty($values[CandidateFunction::NACELNI_ID])) {
                $votes[] = new Vote($sign, $candidatesByFunction[CandidateFunction::NACELNI_ID][$this->getSingleValue($values[CandidateFunction::NACELNI_ID])]);
            }

            if (! empty($values[CandidateFunction::NACELNIK_ID])) {
                $votes[] = new Vote($sign, $candidatesByFunction[CandidateFunction::NACELNIK_ID][$this->getSingleValue($values[CandidateFunction::NACELNIK_ID])]);
            }

            $nacelnictvo = array_merge(
                $values[CandidateFunction::NACELNICTVO_ID . 'female'],
                $values[CandidateFunction::NACELNICTVO_ID . 'male'],
            );
            foreach ($nacelnictvo as $i) {
                $votes[] = new Vote($sign, $candidatesByFunction[CandidateFunction::NACELNICTVO_ID][$i]);
            }

            foreach ($values[CandidateFunction::URKJ_ID] as $i) {
                $votes[] = new Vote($sign, $candidatesByFunction[CandidateFunction::URKJ_ID][$i]);
            }

            foreach ($values[CandidateFunction::RSRJ_ID] as $i) {
                $votes[] = new Vote($sign, $candidatesByFunction[CandidateFunction::RSRJ_ID][$i]);
            }

            try {
                $this->commandBus->handle(new SaveVotes($votes));
                $this->flashMessage('Tvoje hlasy byly úspěšně uloženy.', 'success');
            } catch (DelegateAlreadyVoted $e) {
                $this->flashMessage('Tvoje hlasování již bylo dříve zaznamenáno. Nelze hlasovat vícekrát!', 'danger');
            }

            $this->redirect('this');
        };

        return $form;
    }

    /** @param int[] $arr */
    private function getSingleValue(array $arr) : ?int
    {
        return empty($arr) ? null : array_values($arr)[0];
    }

    /**
     * @param array<int, array<int, Candidate>> $candidatesByFunction
     *
     * @return  string[]
     */
    private function prepareItems(array $candidatesByFunction, int $functionId) : array
    {
        $res = [];
        if (array_key_exists($functionId, $candidatesByFunction)) {
            foreach ($candidatesByFunction[$functionId] as $c) {
                assert($c instanceof Candidate);
                $res[$c->getId()] = $c->getDisplayName();
            }
        }

        return $res;
    }
}
