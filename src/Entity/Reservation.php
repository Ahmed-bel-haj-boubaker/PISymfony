<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Range( min : "today",notInRangeMessage : "The date must be minimum today " )]
    private ?\DateTimeInterface $dateResevation = null;


    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre de billets est obligatoire")]
    #[Assert\Positive(
        message: 'La valeur doit être positive.',
    )]
    private ?int $nombreBillet = null;

    
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservation')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?MatchF $matchF = null;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Billet", mappedBy="reservation", cascade={"remove"})
    */
    private Collection $billet;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;

    public function __construct()
    {
        $this->matchFs = new ArrayCollection();
        $this->billet = new ArrayCollection();
        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateResevation(): ?\DateTimeInterface
    {
        return $this->dateResevation;
    }

    public function setDateResevation(\DateTimeInterface $dateResevation): self
    {
        $this->dateResevation = $dateResevation;

        return $this;
    }

   
    public function getNombreBillet(): ?int
    {
        return $this->nombreBillet;
    }

    public function setNombreBillet(int $nombreBillet): self
    {
        $this->nombreBillet = $nombreBillet;

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

    public function getMatchF(): ?MatchF
    {
        return $this->matchF;
    }

    public function setMatchF(?MatchF $matchF): self
    {
        $this->matchF = $matchF;

        return $this;
    }

    /**
     * @return Collection<int, Billet>
     */
    public function getBillet(): Collection
    {
        return $this->billet;
    }

    public function addBillet(Billet $billet): self
    {
        if (!$this->billet->contains($billet)) {
            $this->billet->add($billet);
            $billet->setReservation($this);
        }

        return $this;
    }

    public function removeBillet(Billet $billet): self
    {
        if ($this->billet->removeElement($billet)) {
            // set the owning side to null (unless already changed)
            if ($billet->getReservation() === $this) {
                $billet->setReservation(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }
}