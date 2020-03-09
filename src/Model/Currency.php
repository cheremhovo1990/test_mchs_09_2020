<?php

declare(strict_types=1);


namespace App\Model;


/**
 * Class Currency
 * @package App\Model
 */
class Currency
{
    /**
     * @var
     */
    private $numCode;
    /**
     * @var
     */
    private $charCode;
    /**
     * @var
     */
    private $name;
    /**
     * @var float
     */
    private $value;

    /**
     * @var \DateTime
     */
    private \DateTime $datetime;

    /**
     * @var
     */
    private $nominal;

    /**
     * Currency constructor.
     * @param $numCode
     * @param $charCode
     * @param $nominal
     * @param $name
     * @param float $value
     * @param \DateTime $datetime
     */
    public function __construct($numCode, $charCode, $nominal, $name, float $value, \DateTime $datetime)
    {
        $this->numCode = $numCode;
        $this->charCode = $charCode;
        $this->name = $name;
        $this->value = $value;
        $this->datetime = $datetime;
        $this->nominal = $nominal;
    }

    /**
     * @return mixed
     */
    public function getNumCode()
    {
        return $this->numCode;
    }

    /**
     * @return mixed
     */
    public function getCharCode()
    {
        return $this->charCode;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @return mixed
     */
    public function getNominal()
    {
        return $this->nominal;
    }
}