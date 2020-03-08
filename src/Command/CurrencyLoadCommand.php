<?php

namespace App\Command;

use App\Service\CurrencyRepository;
use App\Service\CurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CurrencyLoadCommand extends Command
{
    protected static $defaultName = 'currency:load';
    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(CurrencyService $currencyService, string $name = null)
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

        try {
            $this->currencyService->load();
        } catch (\Throwable $e) {
            throw $e;
        }


        $io->success('Успешно');

        return 0;
    }
}
