<?php

namespace App\Entity;

use App\Repository\ChefsSliderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChefsSliderRepository::class)
 */
class ChefsSlider
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/[$&+,:;=?@#|'<>.^*()%!-]/",
     *     match=false,
     *     message="regex.special_chars"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="len.title"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/[$&+,:;=?@#|'<>.^*()%!-]/",
     *     match=false,
     *     message="regex.special_chars"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="len.title"
     * )
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize="2048k",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"}
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="len.photo"
     * )
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @Assert\Length(
     *     max=255,
     *     maxMessage="about.len.video_link"
     * )
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @Assert\Length(
     *     max=255,
     *     maxMessage="about.len.video_link"
     * )
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @Assert\Length(
     *     max=255,
     *     maxMessage="about.len.video_link"
     * )
     */
    private $instagram;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }
}
