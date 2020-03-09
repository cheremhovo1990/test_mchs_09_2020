<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\Table(name="currency",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="currency_un_date_char_code",columns={"date", "char_code"})}
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
     * @ORM\Column(type="integer")
     */
    private $num_code;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $char_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCode(): ?int
    {
        return $this->num_code;
    }

    public function setNumCode(int $num_code): self
    {
        $this->num_code = $num_code;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->char_code;
    }

    public function setCharCode(string $char_code): self
    {
        $this->char_code = $char_code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNominal(): ?int
    {
        return $this->nominal;
    }

    public function setNominal(int $nominal): self
    {
        $this->nominal = $nominal;

        return $this;
    }
}
