<?php

namespace App\EventListener;

use Michelf\MarkdownInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Twig\Environment;

class converseMDVersion
{
    private $mardown;
    private $twig;

    public function __construct(MarkdownInterface $mardown, Environment $twig)
    {
        $this->mardown = $mardown;
        $this->twig = $twig;
    }

    public function OnKernelController(FilterControllerEvent $event)
    {
        // Récupération du contrôleur qui sera appelé depuis $event
        $controller = $event->getController()[0];

        // Récupération du contenu du fichier markdown
        $nameFile = '../version.md';
        $file = fopen($nameFile, 'rb');
        $content = fread($file, filesize($nameFile));

        $content = $this->mardown->transform($content);

        // Transmettre à Twig dans une variable global
        $this->twig->addGlobal('versionMD', $content);
    }
}