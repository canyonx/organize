<?php

namespace App\Command;

use App\Entity\Trip;
use App\Entity\TripRequest;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:demo',
    description: 'Add a short description for your command',
)]
class DemoCommand extends Command
{
    public function __construct(
        private TripRepository $tripRepository,
        private EntityManagerInterface $em,
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

        $exemples = $this->tripRepository->findByTitle('Exemple');

        foreach ($exemples as $exemple) {
            $exemple->setDateAt(new \DateTimeImmutable($exemple->getDateAt()->format('Y-m-d H:i') . ' + 1 day'));

            foreach ($exemple->getTripRequests() as $tr) {
                if ($tr->getStatus() != TripRequest::OWNER)
                    $this->em->remove($tr);
            }
        }

        $this->em->flush();

        $io->section('exemple date : ' . $exemple->getDateAt()->format('d m Y'));

        return Command::SUCCESS;
    }
}
