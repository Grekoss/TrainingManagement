<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\FunctionEnum;
use App\Enum\RoleEnum;
use App\Service\Slugger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    private $container;
    private $encoder;
    private $slugger;

    public function __construct(ContainerInterface $container, UserPasswordEncoderInterface $encoder, Slugger $slugger)
    {
        parent::__construct();

        $this->container = $container;
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    protected static $defaultName = 'app:createAdmin';

    protected function configure()
    {
        $this
            ->setDescription('Creation d\'un compte ADMIN')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();

        $user = new User();

        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Création d\'un compte Admin : ',
            '==============================',
            ''
            ]);

        $mail = $io->ask('Votre adresse mail');
        $password = $io->askHidden('Votre mot de passe');
        $confirmPassword = $io->askHidden('Confirmer votre mot de passe');

        if ($password !== $confirmPassword) {
            $io->error('Vous n\'avez pas confirmer votre mot de passe, merci de recommencer la création du compte Admin !');
            die;
        }

        $user->setEmail($mail)
            ->setFirstName('ADMIN')
            ->setLastName('ADMIN')
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setIsActive(true)
            ->setRole(RoleEnum::ROLE_ADMIN[0])
            ->setFunction(FunctionEnum::OTHER)
            ->setSlug($this->slugger->slugify($user->getFirstName() . ' ' . $user->getLastName()));

        $em->persist($user);
        $em->flush();

        $io->success('Votre compte vient d\'être validé !');
    }
}
