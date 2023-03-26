<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Reponse")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:5)]
    #[Groups("Reponse")]
    private ?string $reponse = null;
    #[ORM\ManyToOne(targetEntity: Reclamation::class, inversedBy: 'reponse')]

   
    private ?Reclamation $reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }
}
