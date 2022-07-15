<?php

namespace App\Entity;

use App\Repository\ChefsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChefsRepository::class)
 */
class Chefs
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
}
