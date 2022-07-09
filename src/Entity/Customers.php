<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomersRepository::class)
 */
class Customers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=OffersMenus::class, inversedBy="customers")
     */
    private $offer_menu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_arrived;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_canceled;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOfferMenu(): ?OffersMenus
    {
        return $this->offer_menu;
    }

    public function setOfferMenu(?OffersMenus $offer_menu): self
    {
        $this->offer_menu = $offer_menu;

        return $this;
    }

    public function getDateArrived(): ?\DateTimeInterface
    {
        return $this->date_arrived;
    }

    public function setDateArrived(\DateTimeInterface $date_arrived): self
    {
        $this->date_arrived = $date_arrived;

        return $this;
    }

    public function isIsCanceled(): ?bool
    {
        return $this->is_canceled;
    }

    public function setIsCanceled(bool $is_canceled): self
    {
        $this->is_canceled = $is_canceled;

        return $this;
    }
}
