<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'equipeA', targetEntity: MatchF::class)]
    private Collection $matchFs;

    #[ORM\OneToMany(mappedBy: 'equipeB', targetEntity: MatchF::class)]
    private Collection $matchF;

    #[ORM\Column]
    private ?int $classement = null;

    #[ORM\Column]
    private ?int $points = null;

    

    

   

    public function __construct()
    {
        $this->matchFs = new ArrayCollection();
        $this->matchF = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
            $matchF->setEquipeA($this);
        }

        return $this;
    }

    public function removeMatchF(MatchF $matchF): self
    {
        if ($this->matchFs->removeElement($matchF)) {
            // set the owning side to null (unless already changed)
            if ($matchF->getEquipeA() === $this) {
                $matchF->setEquipeA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MatchF>
     */
    public function getMatchF(): Collection
    {
        return $this->matchF;
    }

    public function getClassement(): ?int
    {
        return $this->classement;
    }

    public function setClassement(int $classement): self
    {
        $this->classement = $classement;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    
    

    

    
}
