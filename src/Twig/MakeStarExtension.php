<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MakeStarExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('makeStar', [$this, 'makeStar']),
        ];
    }

    public function makeStar($rate, $bestValue = 5)
    {
        // Extraction de la partie entière de la note (qu'elle soit décimale ou non)
        $intRate = $rate;

        // Calcul de la partie déciment éventuelle en %
        $decRate = (floatval($rate) - $intRate) * 100;

        // Insertion des microformats et microdatas
        $ratingBox = '<p class="ratingBox-event" itemprop="aggregateRating" itemscope itemtype="http://schema.xyz/AggregateRating">' . PHP_EOL;
        $ratingBox .= '<span title="' . $rate . ' / ' . $bestValue . '">' . PHP_EOL;

        // Génération du nombre d'étoiles correspondant au maximum possible
        for ($i = 0; $i < $bestValue; $i++) {
            $ratingBox .= '<svg height="16" width="16">' . PHP_EOL;

            // Etoile(s) totalement jaune(s) calculée(s) sur la valeur entière de la note
            if ($i < $intRate) {
                $ratingBox .= '<polygon points="8,0 10.5,5 16,6 12,10 13,16 8,13 3,16 4,10 0,6 5.5,5" fill="Yellow" stroke="Darkkhaki" stroke-width=".5" />' . PHP_EOL;
            } elseif ($i == $intRate && $decRate > 0) {
                // Etoile partiellement jaune basée sur la valeur décimale de la note
                $ratingBox .= '<defs>' . PHP_EOL;
                $ratingBox .= '<linearGradient id="starGradient">' . PHP_EOL;
                $ratingBox .= '<stop offset ="' . $decRate . '%" stop-color="Yellow"/>' . PHP_EOL;
                $ratingBox .= '<stop offset ="' . $decRate . '%" stop-color="LightGrey"/>' . PHP_EOL;
                $ratingBox .= '</linearGradient>' . PHP_EOL;
                $ratingBox .= '</defs>' . PHP_EOL;
                $ratingBox .= '<polygon points="8,0 10.5,5 16,6 12,10 13,16 8,13 3,16 4,10 0,6 5.5,5" fill="url(#starGradient)" stroke="Darkkhaki" stroke-width=".5" />' . PHP_EOL;
            } else {
                $ratingBox .= '<polygon points="8,0 10.5,5 16,6 12,10 13,16 8,13 3,16 4,10 0,6 5.5,5" fill="LightGrey" stroke="DimGray" stroke-width=".5" />' . PHP_EOL;
            }
            $ratingBox .= '</svg>' . PHP_EOL;
        }
        $ratingBox .= '</span>' . PHP_EOL;
        $ratingBox .= '</p>' . PHP_EOL;

        return $ratingBox;
    }
}
