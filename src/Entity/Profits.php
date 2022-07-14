<?php

namespace App\Entity;

use App\Repository\ProfitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfitsRepository::class)
 */
class Profits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $profit;

    /**
     * @ORM\Column(type="float")
     */
    private $loss;

    /**
     * @ORM\Column(name="profit_date", type="date")
     */
    private $profit_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getLoss(): ?float
    {
        return $this->loss;
    }

    public function setLoss(float $loss): self
    {
        $this->loss = $loss;

        return $this;
    }

    public function getProfitDate(): ?\DateTimeInterface
    {
        return $this->profit_date;
    }

    public function setProfitDate(\DateTimeInterface $profit_date): self
    {
        $this->profit_date = $profit_date;

        return $this;
    }
}
