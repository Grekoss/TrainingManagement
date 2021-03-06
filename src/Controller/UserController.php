<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewUserType;
use App\Form\UserProfileType;
use App\Repository\InvitationRepository;
use App\Repository\MentorRepository;
use App\Repository\ResultRepository;
use App\Service\Slugger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function profile(User $user, MentorRepository $mentorRepository, ResultRepository $resultRepository)
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

        // Controle si l'utilisateur n'est pas un mentor
        if ($mentor) {
            $mentor = $mentor->getMentor();
        }

        // Connaitre tout les résultat de l'étudiant
        $results = $resultRepository->findByUser($this->getUser());

        return $this->render('user/profile.html.twig', [
            'mentor' => $mentor,
            'results' => $results
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

    /**
     * @Route("/register/{token}", name="app_user_new")
     */
    public function createUser($token, InvitationRepository $invitationRepository, Request $request, UserPasswordEncoderInterface $encoder, Slugger $slugger, ObjectManager $manager)
    {
        // On recherche si une invitation a été envoyé en vérifiant le Token
        $searchToken = $invitationRepository->findOneBy(['token' => $token]);

        $user = new User();

        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on doit comparer le token et l'adresse mail si ok, alors on supprimer l'inviation et confirmer l'inscription sinon, on annonce le problème
            if ($searchToken->getToken() === $token && $searchToken->getMail() === $user->getEmail()) {
                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $user->setSlug($slugger->slugify($user->getFirstName() . ' ' . $user->getLastName()));

                $manager->persist($user);

                $manager->remove($searchToken);

                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre inscription est validé !'
                );

                return $this->redirectToRoute('app_login');

            } else {
                $this->addFlash(
                    'danger',
                    'Vous n\'avez pas access pour vous inscrire ! Si cela est une erreur, merci de contacter un administateur'
                );
            }
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
