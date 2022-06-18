<?php

namespace App\Entity;

use App\Repository\DayTimesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DayTimesRepository::class)
 */
class DayTimes
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
    private $day_time;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="day_time")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity=OffersMenus::class, mappedBy="day_time")
     */
    private $offersMenuses;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->offersMenuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayTime(): ?string
    {
        return $this->day_time;
    }

    public function setDayTime(string $day_time): self
    {
        $this->day_time = $day_time;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setDayTime($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getDayTime() === $this) {
                $contact->setDayTime(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OffersMenus>
     */
    public function getOffersMenuses(): Collection
    {
        return $this->offersMenuses;
    }

    public function addOffersMenus(OffersMenus $offersMenus): self
    {
        if (!$this->offersMenuses->contains($offersMenus)) {
            $this->offersMenuses[] = $offersMenus;
            $offersMenus->setDayTime($this);
        }

        return $this;
    }

    public function removeOffersMenus(OffersMenus $offersMenus): self
    {
        if ($this->offersMenuses->removeElement($offersMenus)) {
            // set the owning side to null (unless already changed)
            if ($offersMenus->getDayTime() === $this) {
                $offersMenus->setDayTime(null);
            }
        }

        return $this;
    }
}
