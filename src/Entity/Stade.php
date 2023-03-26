<?php

namespace App\Entity;

use App\Repository\StadeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StadeRepository::class)]
class Stade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'stade', targetEntity: MatchF::class)]
    private Collection $matchFs;



    

    public function __construct()
    {
        $this->matchFs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    
    public function __toString()
    {
        return $this->nom; 
    }

    /**
     * @return Collection<int, MatchF>
     */
    public function getMatchFs(): Collection
    {
        return $this->matchFs;
    }

    public function addMatchF(MatchF $matchF): self
    {
        if (!$this->matchFs->contains($matchF)) {
            $this->matchFs->add($matchF);
            $matchF->setStade($this);
        }

        return $this;
    }

    public function removeMatchF(MatchF $matchF): self
    {
        if ($this->matchFs->removeElement($matchF)) {
            // set the owning side to null (unless already changed)
            if ($matchF->getStade() === $this) {
                $matchF->setStade(null);
            }
        }

        return $this;
    }

    
}
