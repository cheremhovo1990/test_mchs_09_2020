<?php

declare(strict_types=1);


namespace App\Search;


use App\Entity\CurrencyUnit;
use App\Repository\CurrencyRepository;

/**
 * Class CurrencySearch
 * @package App\Search
 */
class CurrencySearch
{
    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;

    /**
     * CurrencySearch constructor.
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param array $params
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function search(array $params)
    {
        $sql = $this->currencyRepository->getQuery();

        if (!empty($params['currency_unit_id'])) {
            $sql->innerJoin(CurrencyUnit::class, 'cu', 'cu.id = c.currencyUnit');
            $sql->andWhere('c.currencyUnit = :currency_unit_id')
                ->setParameter('currency_unit_id', $params['currency_unit_id']);
        }
        if (!empty($params['date'])) {
            $sql->andWhere('c.date = :date')
                ->setParameter('date', $params['date']);
        }
        $sort = 'c.id';
        $direction = 'desc';
        if (!empty($params['sort'])) {
            $sort = $params['sort'];
            $direction = $params['direction'];
        }
        $sql->orderBy($sort, $direction);
        return $sql;
    }
}