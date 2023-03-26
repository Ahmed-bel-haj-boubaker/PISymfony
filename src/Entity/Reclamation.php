<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column,Groups('reclamation')]
    private ?int $id = null;

    #[ORM\Column(length: 255),Groups('reclamation')]
    private ?string $sujet = null;

    #[ORM\Column(length: 255),Groups('reclamation')]
    #[Assert\Length(min:10,)]
    private ?string $descreption = null;

   
    #[ORM\Column(length: 255),Groups('reclamation')]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE),Groups('reclamation')]
    private ?\DateTimeInterface $dateR = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\OneToMany(targetEntity:Reponse::class, mappedBy: 'reclamation')]
    
    private Collection $reponse;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reclamation'),Groups('reclamation')]

    private ?User $user = null;

    public function __construct()
    {
        $this->reponse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    public function setDescreption(string $descreption): self
    {
        $this->descreption = $descreption;

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

    public function getDateR(): ?\DateTimeInterface
    {
        return $this->dateR;
    }

    public function setDateR(\DateTimeInterface $dateR): self
    {
        $this->dateR = $dateR;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, reponse>
     */
    public function getReponse(): Collection
    {
        return $this->reponse;
    }

    public function addReponse(reponse $reponse): self
    {
        if (!$this->reponse->contains($reponse)) {
            $this->reponse->add($reponse);
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(reponse $reponse): self
    {
        if ($this->reponse->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

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
