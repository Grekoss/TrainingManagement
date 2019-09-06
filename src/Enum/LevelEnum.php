<?php

namespace App\Enum;

/**
 * List of level
 * #, name, color, backgroundColor
 */
class LevelEnum extends AbstractEnum
{
    public const EASY = [1, 'Facile', '#fff', '#28a745'];
    public const MEDIUM = [2, 'Moyen', '#212529', '#ffc107'];
    public const HARD = [3, 'Difficile', '#fff', '#dc354'];
}
