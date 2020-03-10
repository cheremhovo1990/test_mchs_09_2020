<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyUnitRepository")
 * @ORM\Table(name="currency_unit",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="currency_unit_un_char_code",columns={"char_code"})}
 * )
 */
class CurrencyUnit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="num_code",type="integer")
     */
    private $numCode;

    /**
     * @ORM\Column(name="char_code", type="string", length=3)
     */
    private $charCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Currency", mappedBy="currencyUnit")
     */
    private $currencies;

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
    }

    public static function create(int $numCode, string $charCode, string $name)
    {
        $model = new static();
        return $model->setNumCode($numCode)->setCharCode($charCode)->setName($name);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCode(): ?int
    {
        return $this->numCode;
    }

    public function setNumCode(int $numCode): self
    {
        $this->numCode = $numCode;

        return $this;
    }

    public function getCharCode(): ?string
    {
        return $this->charCode;
    }

    public function setCharCode(string $charCode): self
    {
        $this->charCode = $charCode;

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

    /**
     * @return Collection|Currency[]
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(Currency $currency): self
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies[] = $currency;
            $currency->setCurrencyUnit($this);
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): self
    {
        if ($this->currencies->contains($currency)) {
            $this->currencies->removeElement($currency);
            // set the owning side to null (unless already changed)
            if ($currency->getCurrencyUnit() === $this) {
                $currency->setCurrencyUnit(null);
            }
        }

        return $this;
    }
}
