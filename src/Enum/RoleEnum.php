<?php

namespace App\Enum;

/**
 * List of roles
 */
class RoleEnum extends AbstractEnum
{
    public const ROLE_USER = ['ROLE_USER', 'Etudiant'];
    public const ROLE_TEACHER = ['ROLE_TEACHER', 'Formateur'];
    public const ROLE_ADMIN = ['ROLE_ADMIN', 'Admin'];
}
