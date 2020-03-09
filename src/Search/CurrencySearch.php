<?php

declare(strict_types=1);


namespace App\Search;


use App\Repository\CurrencyRepository;

class CurrencySearch
{
    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function search(array $params)
    {
        $sql = $this->currencyRepository->getQuery();
        if (!empty($params['char_code'])) {
            $sql->andWhere('c.char_code = :char_code')
                ->setParameter('char_code', $params['char_code']);
        }
        if (!empty($params['datetime'])) {
            $sql->andWhere('c.datetime = :datetime')
                ->setParameter('datetime', $params['datetime']);
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