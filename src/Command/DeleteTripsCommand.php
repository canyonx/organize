<?php

namespace App\Command;

use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-trips',
    description: 'Add a short description for your command',
)]
class DeleteTripsCommand extends Command
{
    public function __construct(
        private TripRepository $tripRepository,
        private EntityManagerInterface $em
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

        $trips = $this->tripRepository->findByPeriod('<');

        foreach ($trips as $trip) {
            $this->em->remove($trip);
        }

        $this->em->flush();

        $io->success('Trips passés supprimés');

        return Command::SUCCESS;
    }
}
