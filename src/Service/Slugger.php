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

        // Suppression des accents
        $chaine = str_replace(
            ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'],
            ['A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'],
            $chaine
        );

        // Convertir en minuscule
        $chaine=strtolower($chaine);

        // Remplace les espaces avec $separator
        $chaine= str_replace(' ', $separator, $chaine);

        return substr($chaine, 0, 255);
    }
}
