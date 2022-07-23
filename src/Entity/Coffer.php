<?php

namespace App\Entity;

use App\Repository\CofferRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CofferRepository::class)
 */
class Coffer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Regex (
     *     pattern="/\d/"
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\Regex (
     *     pattern="/[+-]?([0-9]*[.])?[0-9]+/",
     *     message="regex.money"
     * )
     */
    private $money;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoney(): ?float
    {
        return $this->money;
    }

    public function setMoney(float $money): self
    {
        $this->money = $money;

        return $this;
    }
}
