<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Collection;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    // /**
    //  * @return Currency[] Returns an array of Currency objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Currency
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllByCharCode($charCode, $begin, $end)
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select(['datetime', 'value'])
            ->from('currency', 'c')
            ->andWhere('c.char_code = :char_code')
            ->andWhere(':begin >= c.datetime')
            ->andWhere(':end <= c.datetime')
            ->setParameter('char_code', $charCode)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->orderBy('c.datetime', 'ASC')
            ->execute()
            ->fetchAll();
    }

    public function getQuery(): QueryBuilder
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('currency', 'c');
    }

    public function findAllAsArray()
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select(['datetime', 'value', 'char_code'])
            ->from('currency', 'c')
            ->orderBy('c.datetime ASC, c.char_code')
            ->execute()
            ->fetchAll();
    }

    public function getDropDownCharCode()
    {
        return (new Collection($this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('char_code')
            ->from('currency', 'c')
            ->groupBy('char_code')
            ->orderBy('c.char_code', 'ASC')
            ->execute()
            ->fetchAll()))
            ->pluck('char_code', 'char_code')
            ->toArray();
    }
}
