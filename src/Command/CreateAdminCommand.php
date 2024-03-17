<?php

namespace App\Command;

use App\Entity\Homepage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create a admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $encoder
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('pseudo', InputArgument::REQUIRED, 'Nom d\'utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User;
        $password = $this->encoder->hashPassword($user, $input->getArgument('password'));
        $user->setPseudo($input->getArgument('pseudo'))
            ->setPassword($password)
            ->setEmail($input->getArgument('pseudo'))
            ->setRoles(['ROLE_ADMIN'])
            ->setCity('Briançon');

        $home = new Homepage;
        $home->setTitle('OrganiZe')
            ->setSubtitle('Partenaires d\'activités');

        $this->em->persist($user);
        $this->em->persist($home);
        $this->em->flush();

        $io->success('Done');

        return Command::SUCCESS;
    }
}
