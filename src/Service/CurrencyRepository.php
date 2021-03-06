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
    protected string $url;

    /**
     * CurrencyRepository constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

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
                $valute->NumCode->__toString(),
                $valute->CharCode->__toString(),
                $valute->Nominal->__toString(),
                $valute->Name->__toString(),
                (float)str_replace(',', '.', $valute->Value),
                clone $dateTime
            );
        }
        return $result;
    }
}