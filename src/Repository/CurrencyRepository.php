<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    /**
     * CurrencyRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @param $currentUnitId
     * @param $begin
     * @param $end
     * @return mixed[]
     */
    public function findAllByCharCode($currentUnitId, $begin, $end)
    {
        return $this->createQueryBuilder('c')
            ->select(['c.date', 'c.value'])
            ->andWhere('c.currencyUnit = :currency_unit_id')
            ->andWhere(':begin <= c.date')
            ->andWhere(':end >= c.date')
            ->setParameter('currency_unit_id', $currentUnitId)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->orderBy('c.date', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('c');
    }

    /**
     * @param $currencyUnitId
     * @param \DateTime $datetime
     * @return Currency|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUnitDate($currencyUnitId, \DateTime $datetime): ?Currency
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.currencyUnit = :currencyUnitId AND c.date = :date')
            ->setParameter('currencyUnitId', $currencyUnitId)
            ->setParameter('date', $datetime->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
