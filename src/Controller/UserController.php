<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\MentorRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}/show", name="app_user_profile")
     *
     * @return RedirectResponse|Response
     */
    public function profile(User $user, MentorRepository $mentorRepository)
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

        $mentor = $mentorRepository->findOneBy(
            ['student' => $this->getUser()->getId()]
        );

        return $this->render('user/profile.html.twig', [
            'mentor' => $mentor->getMentor(),
        ]);
    }

    /**
     * @Route("/user/{slug}/update", name="app_user_update")
     */
    public function update(User $user, Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
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
        $currentPassword = $user->getPassword();

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si pas de changement de mot de passe :
            if ($user->getPassword() === 'noChange' ) {
                $user->setPassword($currentPassword);
            } else {
                $oldPasswordInput = $request->request->get('oldPassword');

                // https://www.php.net/manual/fr/function.password-verify.php -> notice de la fonction password_verify
                if (! password_verify($oldPasswordInput, $currentPassword)) {
                    $this->addFlash(
                        'danger',
                        'Nous ne pouvons pas sauvergarder vos modifications, car votre ancien mot de passe ne correspond pas à celui fourni !'
                    );

                    return $this->redirectToRoute('app_user_update', [
                        'slug' => $this->getUser()->getSlug()
                    ]);

                } else {
                    $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                }
            }

            $user->setUpdatedAt(new \DateTime());

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les modifications ont été enregistrées !'
            );

            return $this->redirectToRoute('app_user_profile', [
                'slug' => $user->getSlug()
            ]);

        }


        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
