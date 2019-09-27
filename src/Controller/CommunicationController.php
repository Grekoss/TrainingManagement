<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Report;
use App\Entity\User;
use App\Form\SendMessageCommentType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpParser\Node\Stmt\Return_;
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
        $sender = $messageRepository->findBy(['sender' => $this->getUser()]);

        $received = $messageRepository->findBy(['received' => $this->getUser()]);

        $messages = $messageRepository->messagesByUser($this->getUser());

        dump($sender);
        dump($received);
        dump($messages);

        return $this->render('communication/index.html.twig', [
            'controller_name' => 'CommunicationController',
        ]);
    }

    /**
     * @Route("/communication/sendMessage/{user}/{report}", name="app_send_message_report")
     */
    public function sendMessageReport($user, $report, Request $request, ObjectManager $manager, UserRepository $userRepository)
    {
        // $user ayant comme information l'ID de celui-ci, on doit lui retourner l'object User au complet à partir de celui ci
        $user = $userRepository->findOneBy(['id' => $user]);

        $message = new Message();
        $form = $this->createForm(SendMessageCommentType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $message->setSender($this->getUser())
                ->setReceived($user);

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
            'form' => $form->createView()
        ]);
    }
}
