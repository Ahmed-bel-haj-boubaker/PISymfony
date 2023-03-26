<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 
    /**
     * @Assert\NotBlank(message="Please enter a commentaire")
     * @Assert\Length(
     *     min=3,
     *     max=10000,
     *     minMessage="Commentaire must be at least {{ limit }} characters long",
     *     maxMessage="Commentaire cannot be longer than {{ limit }} characters"
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $contenuCom = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateC = null;

 
    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaire')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuCom(): ?string
    {
        return $this->contenuCom;
    }

    public function setContenuCom(string $contenuCom): self
    {
        $this->contenuCom = $contenuCom;

        return $this;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->dateC;
    }

    public function setDateC(\DateTimeInterface $dateC): self
    {
        $this->dateC = $dateC;

        return $this;
    }
   

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
