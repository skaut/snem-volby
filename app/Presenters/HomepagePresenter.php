<?php

declare(strict_types=1);

namespace App;

final class HomepagePresenter extends BasePresenter
{
    public function renderDefault() : void
    {
        $this->template->setParameters(['hideNavBar' => true]);
    }
}
