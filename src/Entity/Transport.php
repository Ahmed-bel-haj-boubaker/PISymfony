<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imageTransport = null;
    #[Assert\Length(
        min: 10,
        minMessage: 'type transport must be at least {{ limit }} characters long',
       )]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom est obligatoire")]
      private ?string $nomTransport = null;
    #[ORM\ManyToMany(targetEntity: Localisation::class, inversedBy: 'transports')]
    private Collection $localisation ;

    #[ORM\ManyToOne(inversedBy: 'transports',targetEntity: CategoryTransport::class)]
    private ?categoryTransport $categoryTransport = null;

    public function __construct()
    {
        $this->localisation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageTransport(): ?string
    {
        return $this->imageTransport;
    }

    public function setImageTransport(string $imageTransport): self
    {
        $this->imageTransport = $imageTransport;

        return $this;
    }

    public function getNomTransport(): ?string
    {
        return $this->nomTransport;
    }

    public function setNomTransport(string $nomTransport): self
    {
        $this->nomTransport = $nomTransport;

        return $this;
    }

    /**
     * @return Collection<int, localisation>
     */
    public function getLocalisation(): Collection
    {
        return $this->localisation;
    }

    public function addLocalisation(localisation $localisation): self
    {
        if (!$this->localisation->contains($localisation)) {
            $this->localisation->add($localisation);
        }

        return $this;
    }

    public function removeLocalisation(localisation $localisation): self
    {
        $this->localisation->removeElement($localisation);

        return $this;
    }

    public function getCategoryTransport(): ?categoryTransport
    {
        return $this->categoryTransport;
    }

    public function setCategoryTransport(?categoryTransport $categoryTransport): self
    {
        $this->categoryTransport = $categoryTransport;

        return $this;
    }
}
