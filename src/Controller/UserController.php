<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}/show", name="app_user_profile")
     *
     * @return RedirectResponse|Response
     */
    public function profile(User $user)
    {
        // Controle si c'est pour afficher le profile de l'utilisateur connecté
        if ( $this->getUser() !== $user)
        {
            $this->addFlash(
                'warning',
                '<i class="fas fa-ban"></i>Vous ne pouvez pas accéder à cette page, ceci n\'est pas votre compte !<i class="fas fa-ban"></i>'
            );

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('user/profile.html.twig', [
        ]);
    }

    /**
     * @Route("/user/{slug}/update", name="app_user_update")
     */
    public function update(User $user, Request $request)
    {
        // Controle si c'est pour modifier son profile et non celui d'un autre
        if ( $this->getUser() !== $user )
        {
            $this->addFlash(
                'danger',
                '<i class="fas fa-ban"></i>Vous ne pouvez pas accéder à cette page, ceci n\'est pas votre compte !<i class="fas fa-ban"></i>'
            );

            return $this->redirectToRoute('app_dashboard');
        }

        // Récupération de l'ancien mot de passe pour le comparer lors de la modification
        $oldPassword = $user->getPassword();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);


        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
