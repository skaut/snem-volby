<?php

declare(strict_types=1);

namespace Model\Objection\Commands;

use Model\Objection\Handlers\SaveObjectionHandler;
use Model\Objection\Objection;

/**
 * @see SaveObjectionHandler
 */
final class SaveObjection
{
    private Objection $objection;

    public function __construct(Objection $objection)
    {
        $this->objection = $objection;
    }

    public function getObjection() : Objection
    {
        return $this->objection;
    }
}
