<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\HebergementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Hebergement")]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $image = null;

    #[Assert\Length(
        min: 10,
        minMessage: 'Your  namecategory must be at least {{ limit }} characters long',
       )]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom est obligatoire")]
    #[Groups("Hebergement")]

    private ?string $nomHeberg = null;
    #[Assert\Length(
        min: 10,
        minMessage: 'Your description must be at least {{ limit }} characters long',
       )]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"la desc est obligatoire")]
    #[Groups("Hebergement")]

    private ?string $deschebergement = null;

    #[ORM\ManyToOne(targetEntity: Localisation::class, inversedBy: 'hebergements')]
    private ?localisation $localisation = null;

    #[ORM\ManyToOne(targetEntity: CategoryHebergement::class, inversedBy: 'hebergements')]
    private ?categoryhebergement $categoryHebergement = null;

    public function __toString() {
        return $this->id;
    }

    public function getId(): ?int
    {
        return  $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNomHeberg(): ?string
    {
        return $this->nomHeberg;
    }

    public function setNomHeberg(string $nomHeberg): self
    {
        $this->nomHeberg = $nomHeberg;

        return $this;
    }

    public function getDescHebergement(): ?string
    {
        return $this->deschebergement;
    }

    public function setDescHebergement(string $deschebergement): self
    {
        $this->deschebergement = $deschebergement;

        return $this;
    }

    public function getLocalisation(): ?localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getCategoryHebergement(): ?categoryhebergement
    {
        return $this->categoryHebergement;
    }

    public function setCategoryHebergement(?categoryhebergement $categoryHebergement): self
    {
        $this->categoryHebergement = $categoryHebergement;

        return $this;
    }

   
}
