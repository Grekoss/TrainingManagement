<?php

namespace App\Controller;

use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index()
    {
        $today = new \DateTime();

        // Enregistrer la connection
        $user = $this->getUser();
        $user->setLastLogin($today);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        // Compare la date du dernier rapport avec la date actuelle et si celle ci supérieur à 7 jours, affiche un message d'alert seulement pour les étudiants!
        $lastReport = $this->reportRepository->findOneBy(['student' => $this->getUser()], ['dateAt' => 'desc']);

        if(!empty($lastReport) && ($user->getRole() === 'Etudiant')) {
            $interval = date_diff($lastReport->getDateAt(), $today);
            $interval = intval($interval->format('%R%a'));

            // On vérifie si cela fait plus de 7 jours qu'aucun rapport n'a été publié par l'utlisateur
            if($interval>7) {
                $this->addFlash('danger', 'Plus de 7 jours sans rapport posté !!!');
            }
        }


        return $this->render('main/dashboard.html.twig', [
        ]);
    }
}
