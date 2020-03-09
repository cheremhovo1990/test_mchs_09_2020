<?php

declare(strict_types=1);


namespace App\Service;


use App\Model\Currency;
use GuzzleHttp\Client;

/**
 * Class CurrencyRepository
 * @package App\Service
 */
class CurrencyRepository
{
    /**
     * @var string
     */
    protected $url = 'http://www.cbr.ru/scripts/XML_daily.asp';


    /**
     * @param \DateTime $dateTime
     * @return array|Currency[]
     */
    public function get(\DateTime $dateTime): array
    {
        $client = new Client();
        $response = $client->get($this->url, ['query' => ['date_req' => $dateTime->format('d/m/Y')]]);
        $contents = $response->getBody()->getContents();
        $document = simplexml_load_string($contents);
        $result = [];


        foreach ($document->Valute as $valute) {
            $result[] = new Currency(
                $valute->NumCode,
                $valute->CharCode,
                $valute->Nominal,
                $valute->Name,
                (float)str_replace(',', '.', $valute->Value),
                clone $dateTime
            );
        }
        return $result;
    }
}