<?php

declare(strict_types=1);


namespace App\Repository;


use App\Entity\CurrencyUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * Class CurrencyUnitRepository
 * @package App\Repository
 * @method CurrencyUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyUnit[]    findAll()
 * @method CurrencyUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyUnitRepository extends ServiceEntityRepository
{
    /**
     * CurrencyUnitRepository constructor.
     * @param ManagerRegistry $registry
     * @param $entityClass
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyUnit::class);
    }

    /**
     * @param $charCode
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCharCode($charCode)
    {
        return $this->createQueryBuilder('cu')
            ->andWhere('cu.charCode = :char_code')
            ->setParameter('char_code', $charCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}