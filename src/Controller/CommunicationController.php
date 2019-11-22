<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\SendMessageReportType;
use App\Form\SendMessageType;
use App\Repository\MentorRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommunicationController extends AbstractController
{
    private $messageRepository;
    private $userRepository;
    private $mentorRepository;
    private $manager;

    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository, MentorRepository $mentorRepository, ObjectManager $manager)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->mentorRepository = $mentorRepository;
        $this->manager = $manager;
    }
    /**
     * @Route("/communication", name="app_communication")
     */
    public function index()
    {
        $allMessages = $this->messageRepository->messagesByUser($this->getUser());

        $messages = [];
        foreach ( $allMessages as $message) {
            $interlocutor = $message->getInterlocutors();
            // On supprime l'user courrant du tableau
            unset($interlocutor[array_search($this->getUser(), $interlocutor)]);
            // On position l'interlocuteur en index 0
            $interlocutor = array_values($interlocutor);

            if (!array_key_exists($interlocutor[0]->getId(), $messages)) {
                if ($interlocutor[0]->getIsActive()) {
                    $messages[$interlocutor[0]->getId()] = $message;
                }
            }
        }

        return $this->render('communication/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/communication/sendMessage/{user}/{report}", name="app_send_message_report")
     */
    public function sendMessageReport($user, $report, Request $request)
    {
        // $user ayant comme information l'ID de celui-ci, on doit lui retourner l'object User au complet à partir de celui ci
        $userReceived = $this->userRepository->findOneBy(['id' => $user]);

        $message = new Message();
        $form = $this->createForm(SendMessageReportType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $message->setSender($this->getUser())
                ->setReceived($userReceived);

            $this->manager->persist($message);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Le message a bien été envoyé !'
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

    /**
     * @Route("/communication/showMessages/{id}", name="app_show_messages")
     */
    public function showMessages($id, Request $request)
    {
        // Liste des tous les messages envoyés et reçus entre les deux utilisateurs
        $sender = $this->messageRepository->messagesForTwoUsers($this->getUser(), $id);
        $received = $this->messageRepository->messagesForTwoUsers($id, $this->getUser());

        // Controler tout les messages reçus et inidquer que maintenant ils sont lus.
        foreach($received as $message) {
            $message->setIsRead(true);

            $this->manager->persist($message);
            $this->manager->flush();
        }

        $userReceived = $this->userRepository->findOneBy(['id' => $id]);

        $messages = array_merge($sender, $received);

        $message = new Message();
        $form = $this->createForm(SendMessageType::class, $message);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $message->setSender($this->getUser())
                ->setReceived($userReceived);

            $this->manager->persist($message);
            $this->manager->flush();

            return $this->redirectToRoute('app_show_messages', ['id'=> $id]);
        }

        return $this->render('communication/show.html.twig', [
            'messages' => $messages,
            'otherUser' => $this->userRepository->find($id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/communication/sendMessage", name="communication_sendMessageForMentor")
     */
    public function sendMessageForMentor(Request $request)
    {
        // On recherche le mentor de l'user
        $mentor = $this->mentorRepository->findOneBy( ['student' => $this->getUser()] );
        $mentor = $mentor->getMentor();

        $message = new Message();
        $form = $this->createForm(SendMessageReportType::class, $message);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $message->setSender($this->getUser())
                ->setReceived($mentor);

            $this->manager->persist($message);
            $this->manager->flush();

            $this->addFlash(
                'info',
                'Votre message pour votre mentor a été correctement envoyé !'
            );

            return $this->redirectToRoute('app_communication');
        }

        return $this->render('communication/sendMessageMentor.html.twig', [
            'form' => $form->createView(),
            'mentor' => $mentor
        ]);
    }
}
