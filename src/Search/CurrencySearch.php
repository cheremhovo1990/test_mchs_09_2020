<?php

declare(strict_types=1);


namespace App\Search;


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
        if (!empty($params['char_code'])) {
            $sql->andWhere('c.char_code = :char_code')
                ->setParameter('char_code', $params['char_code']);
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