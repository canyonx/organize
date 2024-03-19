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
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create a admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $encoder,
        private SluggerInterface $slugger,
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
            ->setEmail($input->getArgument('email'))
            ->setRoles(['ROLE_ADMIN'])
            ->setBirthAt(new \DateTimeImmutable('17-05-1988'))
            ->setCity('Briançon')
            ->setLat(45)
            ->setLng(6)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setLastConnAt(new \DateTimeImmutable())
            ->setSlug(strtolower($this->slugger->slug($input->getArgument('pseudo'))))
            ->setIsVerified(true);

        $home = new Homepage;
        $home->setBackground('backgound')
            ->setSubtitle('subtitle')
            ->setTitle('Title');


        $this->em->persist($user);
        $this->em->persist($home);
        $this->em->flush();

        $io->success('Done');

        return Command::SUCCESS;
    }
}
