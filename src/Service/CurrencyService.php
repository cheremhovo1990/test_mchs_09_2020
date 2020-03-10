<?php

declare(strict_types=1);


namespace App\Service;


use App\Entity\Currency;
use App\Entity\CurrencyUnit;
use App\Repository\CurrencyUnitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CurrencyService
 * @package App\Service
 */
class CurrencyService
{
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var CurrencyUnitRepository
     */
    private CurrencyUnitRepository $currencyUnitRepository;

    /**
     * CurrencyService constructor.
     * @param CurrencyRepository $currencyRepository
     * @param CurrencyUnitRepository $currencyUnitRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CurrencyRepository $currencyRepository,
        CurrencyUnitRepository $currencyUnitRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->currencyRepository = $currencyRepository;
        $this->entityManager = $entityManager;
        $this->currencyUnitRepository = $currencyUnitRepository;
    }

    /**
     * @param \DateTime|null $datetime
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function load(\DateTime $datetime = null)
    {
        $datetime ??= new \DateTime();
        $datum = $this->currencyRepository->get($datetime);


        foreach ($datum as $data) {
            $currencyUnit = $this->getCurrencyUnit($data);
            $currency = Currency::create($data->getNominal(), $data->getValue(), $data->getDatetime());
            $currency->setCurrencyUnit($currencyUnit);
            $this->entityManager->persist($currency);
        }
        $this->entityManager->flush();
    }

    /**
     * @param \App\Model\Currency $currency
     * @return CurrencyUnit
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getCurrencyUnit(\App\Model\Currency $currency): CurrencyUnit
    {
        $currencyUnit = $this->currencyUnitRepository->findOneByCharCode($currency->getCharCode());
        if (is_null($currencyUnit)) {
            $currencyUnit = CurrencyUnit::create($currency->getNumCode(), $currency->getCharCode(), $currency->getName());
            $this->entityManager->persist($currencyUnit);
            $this->entityManager->flush();;
        }
        return $currencyUnit;
    }
}