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
    /**
     * CurrencyRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @param $charCode
     * @param $begin
     * @param $end
     * @return mixed[]
     */
    public function findAllByCharCode($charCode, $begin, $end)
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select(['date', 'value'])
            ->from('currency', 'c')
            ->andWhere('c.char_code = :char_code')
            ->andWhere(':begin <= c.date')
            ->andWhere(':end >= c.date')
            ->setParameter('char_code', $charCode)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->orderBy('c.date', 'ASC')
            ->execute()
            ->fetchAll();
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('currency', 'c');
    }

    /**
     * @return array
     */
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
