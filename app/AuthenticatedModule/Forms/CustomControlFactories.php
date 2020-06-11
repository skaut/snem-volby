<?php

declare(strict_types=1);

namespace App\Forms;

use App\AuthenticatedModule\Components\FormControls\DateControl;

trait CustomControlFactories
{
    public function addDate(string $name, ?string $label = null) : DateControl
    {
        return $this[$name] = new DateControl($label);
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
