<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/[$&+,:;=?@#|'<>.^*()%!-]/",
     *     match=false,
     *     message="regex.special_chars"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\Length(
     *     max=128,
     *     maxMessage="contact.len.email"
     * )
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\Regex(
     *     pattern="/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.0-9]*$/",
     *     message="contact.regex.phone"
     * )
     * @Assert\Length(
     *     max=16,
     *     maxMessage="contact.len.phone"
     * )
     */
    private $phone_number;

    /**
     * @ORM\Column(type="date")
     */
    private $reservation_date;

    /**
     * @ORM\ManyToOne(targetEntity=DayTimes::class, inversedBy="contacts")
     * @Assert\Regex(
     *     pattern="/[$&+,:;=?@#|'<>.^*()%!-]/",
     *     match=false,
     *     message="regex.special_chars"
     * )
     */
    private $day_time;

    /**
     * @ORM\ManyToOne(targetEntity=Guests::class, inversedBy="contacts")
     * @Assert\Regex (
     *     pattern="/\d/"
     * )
     */
    private $guest;

    /**
     * @ORM\Column(type="string", length=1024)
     * @Assert\Length(
     *     max=1024,
     *     maxMessage="contact.len.msg"
     * )
     * @Assert\Regex(
     *     pattern="/[<>]/",
     *     match=false,
     *     message="regex.special_chars"
     * )
     */
    private $msg;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getDayTime(): ?DayTimes
    {
        return $this->day_time;
    }

    public function setDayTime(?DayTimes $day_time): self
    {
        $this->day_time = $day_time;

        return $this;
    }

    public function getGuest(): ?Guests
    {
        return $this->guest;
    }

    public function setGuest(?Guests $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getMsg(): ?string
    {
        return $this->msg;
    }

    public function setMsg(string $msg): self
    {
        $this->msg = $msg;

        return $this;
    }
}
