<?php

declare(strict_types=1);

namespace App\AuthenticatedModule\Forms;

use App\AuthenticatedModule\Components\FormControls\DateControl;
use Nextras\FormComponents\Controls\DateTimeControl;

trait CustomControlFactories
{
    public function addDate(string $name, ?string $label = null) : DateControl
    {
        return $this[$name] = new DateControl($label);
    }

    public function addDateTime(string $name, ?string $label = null) : DateTimeControl
    {
        return $this[$name] = new DateTimeControl($label);
    }

    /**
     * {@inheritDoc}
     */
    public function addContainer($name) : BaseContainer
    {
        $control               = new BaseContainer();
        $control->currentGroup = $this->currentGroup;

        return $this[$name] = $control;
    }
}
