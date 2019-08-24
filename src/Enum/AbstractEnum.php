<?php

namespace App\Enum;

use ReflectionException;

abstract class AbstractEnum
{
    /**
     * Get a list of components
     * @return array Return all constant from the enumeration class as an array
     * @throws ReflectionException
     */
    public static function getConstants(): array
    {
        $oClass = new \ReflectionClass(static::class);

        return $oClass->getConstants();
    }
}
