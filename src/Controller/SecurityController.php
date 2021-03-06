<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use App\Service\Random;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Swift_Mailer;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/forgotPassword", name="app_forgot_password")
     */
    public function forgotPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, ObjectManager $manager, Swift_Mailer $mailer, Random $random)
    {
        $formForgotPassword = $this->createForm(ForgotPasswordType::class);
        $formForgotPassword->handleRequest($request);

        if ($formForgotPassword->isSubmitted() && $formForgotPassword->isValid())
        {
            $email = $formForgotPassword->get('email')->getData();

            // Recherche l'utilisateur avec cette adresse pour créer le nouveau mot de passe
            $user = $userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $this->addFlash(
                    'danger',
                    'Mauvaise adresse! Veuillez saisir une adresse valide !'
                );

                return $this->redirectToRoute('app_forgot_password');
            }

            $newPassword = $random->randomPassword('all', 10);
            $user->setPassword($encoder->encodePassword($user, $newPassword))
                ->setUpdatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();

            $message = new \Swift_Message();
            $message->setSubject('Nouveau Mot de Passe')
                ->setFrom(['grekoss.cedric.dev@gmail.com' => 'Nouveau mot de passe'])
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/forgot.html.twig', [
                            'data' => $formForgotPassword->getData(),
                            'newPassword' => $newPassword,
                            'user' => $user
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash(
                'success',
                'Votre nouveau mot de passe a été envoyé à votre adresse mail !'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotPassword.html.twig', [
            'formForgotPassword' => $formForgotPassword->createView(),
        ]);
    }
}
