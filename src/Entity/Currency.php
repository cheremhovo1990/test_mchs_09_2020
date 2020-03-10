<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\Table(name="currency",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="currency_un_date_currency_unit_id",columns={"date", "currency_unit_id"})}
 * )
 */
class Currency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=3)
     */
    private $value;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $nominal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CurrencyUnit", inversedBy="currencies")
     */
    private $currencyUnit;

    /**
     * Currency constructor.
     * @param $nominal
     * @param $value
     * @param \DateTimeInterface $date
     * @return Currency
     */
    public static function create($nominal, $value, \DateTimeInterface $date)
    {
        $model = new static();
        return $model
            ->setValue($value)
            ->setNominal($nominal)
            ->setDate($date);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    /**
     * @param int $nominal
     * @return $this
     */
    public function setNominal(int $nominal): self
    {
        $this->nominal = $nominal;

        return $this;
    }

    public function getCurrencyUnit(): ?CurrencyUnit
    {
        return $this->currencyUnit;
    }

    public function setCurrencyUnit(?CurrencyUnit $currencyUnit): self
    {
        $this->currencyUnit = $currencyUnit;

        return $this;
    }
}
