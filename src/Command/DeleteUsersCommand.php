<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\NotificationService;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-users',
    description: 'Add a short description for your command',
)]
class DeleteUsersCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private NotificationService $notificationService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findBy(['isVerified' => false]);

        $now = new \DateTimeImmutable();
        $oneHour = \DateInterval::createFromDateString('1 hour');

        foreach ($users as $user) {

            if ($now->sub($oneHour) > $user->getCreatedAt()) {

                // Send an email of error
                $this->notificationService->send(
                    $user,
                    [
                        'title' => 'Email non validé',
                        'message' => "Bonjour,\r\n
                        Votre email n'a pas été validé, le compte associé à été supprimé.\r\n
                        Vous pouvez dès à présent recommencer votre inscription.\r\n
                        Décrire les problèmes rencontrés contact@organize-app.fr",
                    ]
                );

                // Delete User
                // $this->em->remove($user->getSetting());
                $this->em->remove($user);

                $io->section('Delete : ' . $user->getId());
            }
        }

        $this->em->flush();

        $io->success('Utilisateurs non vérifiés supprimés');

        return Command::SUCCESS;
    }
}
