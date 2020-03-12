<?php

namespace App\Command;

use App\Service\CurrencyService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CurrencyLoadCommand
 * @package App\Command
 */
class CurrencyLoadCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'currency:load';
    /**
     * @var CurrencyService
     */
    private $currencyService;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * CurrencyLoadCommand constructor.
     * @param CurrencyService $currencyService
     * @param LoggerInterface $logger
     * @param string|null $name
     */
    public function __construct(
        CurrencyService $currencyService,
        LoggerInterface $logger,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->currencyService = $currencyService;
        $this->logger = $logger;
        $this->name = $name;
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

        try {
            $this->currencyService->load();
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            throw $e;
        }


        $io->success('Успешно');

        return 0;
    }
}
