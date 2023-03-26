<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CategoryHebergementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryHebergementRepository::class)]
class CategoryHebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("CategoryHebergement")]
    private ?int $id = null;
    #[Assert\Length(
        min: 2,
        minMessage: 'name category must be at least {{ limit }} characters long',
       )]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom categorie est obligatoire")]
    #[Groups("CategoryHebergement")]

    private ?string $nomcategory = null;
    
    #[ORM\OneToMany(targetEntity: Hebergement::class, mappedBy: 'categoryHebergement')]

    private Collection $hebergements;

    public function __construct()
    {
        $this->hebergements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString() {
        return $this->id;
    }

    public function getNomcategory(): ?string
    {
        return $this->nomcategory;
    }

    public function setNomcategory(string $nomcategory)
    {
        $this->nomcategory = $nomcategory;

        return $this;
    }

    /**
     * @return Collection<int, Hebergement>
     */
    public function getHebergements(): Collection
    {
        return $this->hebergements;
    }

    public function addHebergement(Hebergement $hebergement): self
    {
        if (!$this->hebergements->contains($hebergement)) {
            $this->hebergements->add($hebergement);
            $hebergement->setCategoryHebergement($this);
        }

        return $this;
    }

    public function removeHebergement(Hebergement $hebergement): self
    {
        if ($this->hebergements->removeElement($hebergement)) {
            // set the owning side to null (unless already changed)
            if ($hebergement->getCategoryHebergement() === $this) {
                $hebergement->setCategoryHebergement(null);
            }
        }

        return $this;
    }
}
