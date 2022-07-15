<?php

namespace App\Entity;

use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AboutRepository::class)
 */
class About
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     * @Assert\Length(
     *     max=255,
     *     maxMessage="about.len.video_link"
     * )
     */
    private $video_link;

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
    private $video_bg;

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

    public function getVideoLink(): ?string
    {
        return $this->video_link;
    }

    public function setVideoLink(?string $video_link): self
    {
        $this->video_link = $video_link;

        return $this;
    }

    public function getVideoBg(): ?string
    {
        return $this->video_bg;
    }

    public function setVideoBg(?string $video_bg): self
    {
        $this->video_bg = $video_bg;

        return $this;
    }
}
