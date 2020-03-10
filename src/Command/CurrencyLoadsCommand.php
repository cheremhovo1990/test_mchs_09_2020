<?php

namespace App\Command;

use App\Service\CurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CurrencyLoadsCommand
 * @package App\Command
 */
class CurrencyLoadsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'currency:loads';
    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    /**
     * CurrencyLoadsCommand constructor.
     * @param CurrencyService $currencyService
     * @param string|null $name
     */
    public function __construct(
        CurrencyService $currencyService,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->currencyService = $currencyService;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

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

        $io->success('Упешно');

        return 0;
    }
}
