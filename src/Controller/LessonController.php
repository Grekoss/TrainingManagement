<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    /**
     * @Route("/lesson", name="app_lesson")
     */
    public function index(LessonRepository $lessonRepository)
    {
        $listFoodSafety = $lessonRepository->findByCategory('Sécurité Alimentaire');
        $listManagement = $lessonRepository->findByCategory('Gestion');
        $listCounter = $lessonRepository->findByCategory('Comptoir');
        $listKitchen = $lessonRepository->findByCategory('Cuisine');
        $listPEP = $lessonRepository->findByCategory('PEP');
        $listAdministration = $lessonRepository->findByCategory('Administration');
        $listVarious = $lessonRepository->findByCategory('Divers');

        return $this->render('lesson/index.html.twig', [
            'listFoodSafety' => $listFoodSafety,
            'listManagement' => $listManagement,
            'listCounter' => $listCounter,
            'listKitchen' => $listKitchen,
            'listPEP' => $listPEP,
            'listAdministration' => $listAdministration,
            'listVarious' => $listVarious
        ]);
    }
}
