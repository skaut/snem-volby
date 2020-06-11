<?php

declare(strict_types=1);

namespace acceptance;

use AcceptanceTester;
use Codeception\Test\Unit;

class LoginTest extends Unit
{
    protected AcceptanceTester $tester;

    public function test() : void
    {
        $this->tester->login($this->tester::UNIT_LEADER_ROLE);
    }
}
