<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Report;
use App\Entity\User;
use App\Form\SendMessageCommentType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommunicationController extends AbstractController
{
    /**
     * @Route("/communication", name="app_communication")
     */
    public function index(MessageRepository $messageRepository)
    {
        $allMessages = $messageRepository->messagesByUser($this->getUser());

        $messages = [];
        foreach ( $allMessages as $message) {
            $interlocutor = $message->getInterlocutors();
            // On supprime l'user corrant du tableau
            unset($interlocutor[array_search($this->getUser(), $interlocutor)]);
            // On position l'interlocuteur en index 0
            $interlocutor = array_values($interlocutor);

            if (!array_key_exists($interlocutor[0]->getId(), $messages)) {
                $messages[$interlocutor[0]->getId()] = $message;
            }
        }
        dump($messages);

        return $this->render('communication/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/communication/sendMessage/{user}/{report}", name="app_send_message_report")
     */
    public function sendMessageReport($user, $report, Request $request, ObjectManager $manager, UserRepository $userRepository)
    {
        // $user ayant comme information l'ID de celui-ci, on doit lui retourner l'object User au complet à partir de celui ci
        $userReceived = $userRepository->findOneBy(['id' => $user]);

        $message = new Message();
        $form = $this->createForm(SendMessageCommentType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $message->setSender($this->getUser())
                ->setReceived($userReceived);

            $manager->persist($message);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le message a bien été encoyé !'
            );

            return $this->redirectToRoute('app_report_show', [
                'id' => $report
            ]);
        }

        return $this->render('communication/sendMessageReport.html.twig', [
            'form' => $form->createView(),
            'userReceived' => $userReceived,
            'report' => $report
        ]);
    }
}
