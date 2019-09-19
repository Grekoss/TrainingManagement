<?php

namespace App\DataFixtures\FakerProvider;

class DataProvider extends \Faker\Provider\Base
{
    protected static $tags = [
        'Sécurité Alimentaire',
        'Cuisine',
        'Comptoir',
        'Responsable de zone',
        'Finance',
        'Gestion des stocks',
        'Gestion du personnels',
        'PEP',
        'Relation clients'
    ];

    protected static $quizzes = [
        'Quelle pièce est absolument à protéger dans un jeu d’échec ?',
        'Quel est la capitale de l’Australie ?',
        'Quelle année a suivi l’an 1 avant JC ?',
        'Qui sont les animateurs du festival « juste pour rire » ?',
        'Qui anime Secret Story?',
        'A combien, d’exemplaire, à 10000 près c’est vendupour toi public 2?',
        'Combien de nouvelles chaînes sont apparus grâce à la TNT ?',
        'Quel est la phrase fétiche de Chevalier et Laspalès ?',
        'Combien y a-t-il de signes astrologiques chinois ?',
        'Quel est le 2ème nom de l’hippocampe ?',
        'En quelle année est mort JFK ?',
        'Combien de dieu trône a l’Olympe ?',
        'Qu’appelle-t-on lacanopée ?',
        'Quelle est le dernier album de Britney Spears ?',
        'Quel l’équivalent du pape au Tibet ?',
        'Quelle est la différence entre le chameau et le dromadaire ?',
        'Quel précipités observe-t-on quand mélange du nitrate d’argent avec du chlore ?',
        'Quelle est la voiture dans Retour vers lefutur ?',
        'Qui est le réalisateur du film « camping » ?',
        'Comment s’appelle l’équivalent du musée Grévin à Londres ?',
        '1+2+3+4 ?',
        'Qui interprète le rôle de Cléopâtre dans la comédie musicale du même nom ?',
        'Quel ville est surnommé « big Apple » aux USA ?',
        'De combien de syllabes est composé un alexandrin ?',
        'De quiest amoureux Juliette ?',
        'Quel est la 1ère émission de télé réalité a avoir été diffuser en France ?',
        'Qui a écrit les misérables ?',
        'Quel animal a-t-on au plafond quand on est un peu fou ?',
        'Comment appelle-t-on la lumière qui se rapproche le plus de la lumière du soleil ?',
        'Quel ancien célèbre pilote de formule 1 varemplacer Felippe Massa dans l’ecurie Ferrari ?',
        'Qui a marqué le troisième but de la finale de la coupe du monde 1998 ?',
        'Comment appelle-t-on les morceaux de tissus généralement placés sur les télés ?',
        'Quel est l’équipe américaine qui a remporté le dernier championnat NBA ?',
        'Quel est le symbole chimique de l’azote ?',
        'Donnez-moiles 4 plages du débarquement 1939-1945?',
        'Citez-moi les 5 sens du corps humain ?',
        'Qu’est-ce qui est dentelle, suzette ou fourrée ?',
        'Qui est le célèbre joueur anglais marié à une spice girl ?',
        'Qui présentez l’émission « Ciel mon Mardi » ?',
        'Quel anniversaire Fort Boyard a-t-ilfêté cette année ?'
    ];

    protected static $positions = [
        'Boissons / Desserts',
        'OAT',
        'Piano',
        'Frites',
        'Encaissement comptoir',
        'Position libre',
        'Cuisson Viandes',
        'Cuisson Fries',
        'UHC',
        'Garnitures',
        'Pains',
        'Casque drive',
        'Controle drive'
    ];

    public function tagName()
    {
        return static::randomElement(static::$tags);
    }

    public function quizName()
    {
        return static::randomElement(static::$quizzes);
    }

    public function positionName()
    {
        return static::randomElement(static::$positions);
    }
}
