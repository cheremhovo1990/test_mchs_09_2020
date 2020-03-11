<?php

declare(strict_types=1);


namespace App\Serializer;


use App\Entity\Currency;

class CurrencySerializer
{
    /**
     * @param array|Currency[] $currencies
     * @return array
     */
    public function serialize(array $currencies): array
    {
        $result = [];
        foreach ($currencies as $currency) {
            $result[] = [
                'id' => $currency->getId(),
                'num_code' => $currency->getCurrencyUnit()->getNumCode(),
                'char_code' => $currency->getCurrencyUnit()->getCharCode(),
                'name' => $currency->getCurrencyUnit()->getName(),
                'value' => $currency->getValue(),
                'date' => $currency->getDate()->format('Y-m-d'),
                'nominal' => $currency->getNominal(),
            ];
        }
        return $result;
    }
}