<?php

declare(strict_types=1);

class Helpers
{

    /**
     * @param object $aggregate
     */
    public static function assignIdentity ($aggregate, int $id) : void {
        $class = new ReflectionClass(get_class ($aggregate));

        $idProperty = $class->getProperty ('id');
        $idProperty->setAccessible (true);

        $idProperty->setValue ($aggregate, $id);
    }
}
