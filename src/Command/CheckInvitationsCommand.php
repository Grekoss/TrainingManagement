<?php

namespace App\Command;

use App\Repository\InvitationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CheckInvitationsCommand extends Command
{
    private $invitationRepository;
    private $container;

    public function __construct(InvitationRepository $invitationRepository, ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
        $this->invitationRepository = $invitationRepository;

    }

    protected static $defaultName = 'app:check-invitations';

    protected function configure()
    {
        $this
            ->setDescription('Vérifie les invitations et nettoyage de la base de données')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Tout supprimer')
            ->addOption('month', null, InputOption::VALUE_NONE, 'Supprimer seulement ceux de plus d\'un mois')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();

        $allInvitations = array();

        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('all')) {
            $allInvitations = $this->invitationRepository->findAll();
        }

        if ($input->getOption('month')) {
            $allInvitations = $this->invitationRepository->findMore1Month();
        }

        $output->writeln([
            '',
            'On a supprimé les invitations suivantes : ',
            '==========================================',
            '']);

        for ( $i=0 ; $i<count($allInvitations) ; $i++ ) {
            $output->writeln($allInvitations[$i]->getMail());

            $em->remove($allInvitations[$i]);
            $em->flush();
        }

        $io->success('Suppression réussite !');
    }
}
