<?php

namespace App\Command;

use App\Entity\CurrencyUnit;
use App\Repository\CurrencyRepository;
use App\Repository\CurrencyUnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class TaskRefactorCurrencyCommand
 * @package App\Command
 */
class TaskRefactorCurrencyCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'task-refactor-currency';
    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;
    /**
     * @var CurrencyUnitRepository
     */
    private CurrencyUnitRepository $currencyUnitRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * TaskRefactorCurrencyCommand constructor.
     * @param CurrencyRepository $currencyRepository
     * @param CurrencyUnitRepository $currencyUnitRepository
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(
        CurrencyRepository $currencyRepository,
        CurrencyUnitRepository $currencyUnitRepository,
        EntityManagerInterface $entityManager,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->currencyRepository = $currencyRepository;
        $this->currencyUnitRepository = $currencyUnitRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $currencies = $this->currencyRepository->findAll();

        foreach ($currencies as $currency) {
            $currencyUnit = $this->currencyUnitRepository->findOneByCharCode($currency->getCharCode());
            if (is_null($currencyUnit)) {
                $currencyUnit = CurrencyUnit::create($currency->getNumCode(), $currency->getCharCode(), $currency->getName());
                $this->entityManager->persist($currencyUnit);
                $this->entityManager->flush();
            }
            $currency->setCurrencyUnit($currencyUnit);
        }
        $this->entityManager->flush();
        return 0;
    }
}
