<?php

declare(strict_types=1);


namespace App\Service;


use App\Entity\Currency;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(CurrencyRepository $currencyRepository, EntityManagerInterface $entityManager)
    {
        $this->currencyRepository = $currencyRepository;
        $this->entityManager = $entityManager;
    }

    public function load(\DateTime $datetime = null)
    {
        $datetime ??= new \DateTime();
        $datum = $this->currencyRepository->get($datetime);


        foreach ($datum as $data) {
            $currency = new Currency(
                $data->getNumCode(),
                $data->getCharCode(),
                $data->getNominal(),
                $data->getName(),
                $data->getValue(),
                $data->getDatetime()
            );
            $this->entityManager->persist($currency);
        }
        $this->entityManager->flush();
    }
}