<?php

namespace App\Command;

use App\Service\MailjetService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:mailtest',
    description: 'Envoyer un mail de test',
)]
class MailtestCommand extends Command
{
    private $mailjet;

    public function __construct(
        // MailerInterface $mailer,
        MailjetService $mailjet
    ) {
        parent::__construct();
        $this->mailjet = $mailjet;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Ecrire dans le terminal
        $io = new SymfonyStyle($input, $output);

        try {
            $this->mailjet->sendNotification(
                'immersioncanyon@gmail.com',
                'immersion',
                'notification',
                [
                    'title' => 'notif test',
                    'message' => 'message test'
                ]
            );
        } catch (TransportExceptionInterface $e) {
            $io->error($e);
            return Command::FAILURE;
        }

        // Retour terminal
        $io->success('Newsletter envoyée');

        return Command::SUCCESS;
    }
}
