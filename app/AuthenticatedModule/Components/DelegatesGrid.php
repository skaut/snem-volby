<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Components;

use App\AuthenticatedModule\Factories\BaseGridControl;
use App\AuthenticatedModule\Factories\GridFactory;
use eGen\MessageBus\Bus\QueryBus;
use Model\Delegate\ReadModel\Queries\DelegatesListQuery;
use Ublaboo\DataGrid\AggregationFunction\FunctionSum;
use Ublaboo\DataGrid\DataGrid;

class DelegatesGrid extends BaseGridControl
{
    private GridFactory $gridFactory;
    private QueryBus $queryBus;

    public function __construct(
        GridFactory $gridFactory,
        QueryBus $queryBus
    ) {
        $this->gridFactory = $gridFactory;
        $this->queryBus    = $queryBus;
    }

    public function createComponentGrid() : DataGrid
    {
        $grid = $this->gridFactory->create();

        $grid->setDataSource($this->queryBus->handle(new DelegatesListQuery()));
        $grid->setDefaultSort(['name' => 'ASC']);

        $grid->addColumnText('name', 'Jméno')
            ->setSortable()
            ->setFilterText();

        $grid->addColumnText('type', 'Typ')
            ->setSortable();

        $grid->addColumnText('unitNumber', 'Jednotka')
            ->setSortable();

        $grid->addColumnText('unitName', 'Název jednotky')
            ->setSortable();

        $grid->addColumnText('participated', 'Účastnil se')
            ->setReplacement([false => 'ne', true => 'ano']);

        $grid->addColumnText('voted', 'Hlasoval')
            ->setReplacement([false => 'ne', true => 'ano']);

        $grid->addAggregationFunction('participated', new FunctionSum('participated'));
        $grid->addAggregationFunction('voted', new FunctionSum('voted'));

        return $grid;
    }
}
