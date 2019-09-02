<?php

namespace App\Service;

class Slugger
{
    // hypen = tirer;
    private $use_hyphen;

    public function __construct($use_hyphen)
    {
        $this->use_hyphen = $use_hyphen;
    }

    public function slugify($strToConvert)
    {
        $separator = $this->use_hyphen ? '-' : '';

        // Créer un array sans les caractères indésirables
        $unwantedChars = array(',', '!', '?', '.', '\"', '\'');
        $chaine = str_replace($unwantedChars, '', $strToConvert);

        // Convertir en minuscule
        $chaine=strtolower($chaine);

        // Remplace les espaces avec $separator
        $chaine= str_replace(' ', $separator, $chaine);

        return substr($chaine, 0, 255);
    }
}
