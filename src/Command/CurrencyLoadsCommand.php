<?php

namespace App\Command;

use App\Service\CurrencyService;
use Carbon\Carbon;
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

    protected function configure()
    {
        $this
            // ...
            ->addArgument('begin', InputArgument::REQUIRED, 'Дата начало загрузки (2018-12-01)')
            ->addArgument('end', InputArgument::OPTIONAL, 'Дата конца загрузки (2020-01-01)')
        ;
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

        if (!$begin = Carbon::createFromFormat('Y-m-d', $input->getArgument('begin'))) {
            $io->error('Дата начало не корректна');
        }
        if (!$end = Carbon::createFromFormat('Y-m-d', $input->getArgument('end'))) {
            $io->error('Дата конца не корректна');
        }
        if ($begin->greaterThan($end)) {
            $io->error('Дата начало больше чем дата конца');
        }
        $datePeriod = new \DatePeriod(
            $begin,
            new \DateInterval('P1M'),
            $end,
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
