<?php

namespace App\Enum;

/**
 * List of function in a restaurant
 */
class FunctionEnum extends AbstractEnum
{
    public const TEAM_MEMBER = 'équipier';
    public const COACH = 'coach';
    public const ZONE_MANAGER = 'responsable de zone';
    public const MANAGER = 'assistant de direction';
    public const DEPUTY_DIRECTOR = 'directeur adjoint';
    public const DIRECTOR = 'directeur';
    public const OTHER = 'autre';
}
