<?php

declare(strict_types=1);

namespace App\Components;

use App\AuthenticatedModule\Components\BaseControl;

final class TipBox extends BaseControl
{
    public function render() : void
    {
        $this->template->setFile(__DIR__ . '/templates/TipBox.latte');
        $this->template->render();
    }
}
