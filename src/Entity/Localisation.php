<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalisationRepository::class)]
class Localisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom est obligatoire")]

    private ?string $lieux = null;

    #[ORM\ManyToMany(targetEntity: Transport::class, mappedBy: 'localisation')]
    private Collection $transports;
    #[ORM\OneToMany(targetEntity: Hebergement::class, mappedBy: 'localisation')]

    private Collection $hebergements;

    public function __construct()
    {
        $this->transports = new ArrayCollection();
        $this->hebergements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        return $this->lieux;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    /**
     * @return Collection<int, Transport>
     */
    public function getTransports(): Collection
    {
        return $this->transports;
    }

    public function addTransport(Transport $transport): self
    {
        if (!$this->transports->contains($transport)) {
            $this->transports->add($transport);
            $transport->addLocalisation($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        if ($this->transports->removeElement($transport)) {
            $transport->removeLocalisation($this);
        }

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
            $hebergement->setLocalisation($this);
        }

        return $this;
    }

    public function removeHebergement(Hebergement $hebergement): self
    {
        if ($this->hebergements->removeElement($hebergement)) {
            // set the owning side to null (unless already changed)
            if ($hebergement->getLocalisation() === $this) {
                $hebergement->setLocalisation(null);
            }
        }

        return $this;
    }
}
