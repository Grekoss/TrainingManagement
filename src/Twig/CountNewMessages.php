<?php

namespace App\Twig;

use App\Repository\MessageRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CountNewMessages extends AbstractExtension
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('notificationMessages', [$this, 'notificationMessages']),
        ];
    }

    /**
     * Fonction retournant le nombre de nouveau messages reçu par l'intermédiaire de cette fonction twig
     */
    public function notificationMessages($user): int
    {
        $total = $this->messageRepository->allMessagesNotReadByUser($user);
        
        return count($total);
    }
}