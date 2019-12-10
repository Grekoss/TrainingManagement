<?php

namespace App\EventListener;

use Michelf\MarkdownInterface;
use Twig\Environment;

class ConverseMDVersion
{
    private $mardown;
    private $twig;

    public function __construct(MarkdownInterface $mardown, Environment $twig)
    {
        $this->mardown = $mardown;
        $this->twig = $twig;
    }

    public function OnKernelController()
    {
        // Récupération du contenu du fichier markdown
        $nameFile = '../version.md';
        $file = fopen($nameFile, 'rb');
        $content = fread($file, filesize($nameFile));

        $content = $this->mardown->transform($content);

        // Transmettre à Twig dans une variable global
        $this->twig->addGlobal('versionMD', $content);
    }
}