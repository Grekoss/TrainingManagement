<?php

namespace App\Enum;

/**
 * List of level
 * nameConst, name, number level, color, backgroundColor
 */
class LevelEnum extends AbstractEnum
{
    public const EASY = ['EASY', 'Facile', 1, '#fff', '#28a745'];
    public const MEDIUM = ['MEDIUM', 'Moyen', 2, '#212529', '#ffc107'];
    public const HARD = ['HARD', 'Difficile', 3, '#fff', '#dc354'];
}
