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
     * @param $num_code
     * @param $char_code
     * @param $nominal
     * @param $name
     * @param $value
     * @param \DateTimeInterface $date
     */
    public function __construct($num_code, $char_code, $nominal, $name, $value,\DateTimeInterface $date)
    {
        $this->num_code = $num_code;
        $this->char_code = $char_code;
        $this->name = $name;
        $this->value = $value;
        $this->date = $date;
        $this->nominal = $nominal;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getNumCode(): ?int
    {
        return $this->num_code;
    }

    /**
     * @param int $num_code
     * @return $this
     */
    public function setNumCode(int $num_code): self
    {
        $this->num_code = $num_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCharCode(): ?string
    {
        return $this->char_code;
    }

    /**
     * @param string $char_code
     * @return $this
     */
    public function setCharCode(string $char_code): self
    {
        $this->char_code = $char_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
