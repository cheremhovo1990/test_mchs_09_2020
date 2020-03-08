<?php

namespace App\Command;

use App\Service\CurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CurrencyLoadsCommand extends Command
{
    protected static $defaultName = 'currency:loads';
    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    public function __construct(
        CurrencyService $currencyService,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->currencyService = $currencyService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $datePeriod = new \DatePeriod(
            \DateTime::createFromFormat('Y.m.d', '2018.1.1'),
            new \DateInterval('P1M'),
            new \DateTime(),
        );

        foreach ($datePeriod as $date) {
            try {
                $this->currencyService->load($date);
            } catch (\Throwable $e) {
                throw $e;
            }
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
