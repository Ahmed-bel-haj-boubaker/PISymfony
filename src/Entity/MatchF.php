<?php

namespace App\Entity;

use App\Repository\MatchFRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MatchFRepository::class)]
class MatchF
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('matchF')]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]
    #[Groups('matchF')]
    #[Assert\Type(
        type: 'integer',
        message: 'La valeur {{ value }} n est pas valide {{ type }}.',
    )]
    #[Assert\Positive(
        message: 'La valeur doit être positive.',
    )]
    private ?int $resultatA = null;

    #[ORM\Column]
    #[Groups('matchF')]
    #[Assert\Type(
        type: 'integer',
        message: 'La valeur {{ value }} n est pas valide {{ type }}.',
    )]
    #[Assert\Positive(
        message: 'La valeur doit être positive.',
    )]
    private ?int $resultatB = null;

    #[ORM\OneToMany(mappedBy: 'matchF', targetEntity: Reservation::class)]
    private Collection $reservation;

    #[ORM\Column]
    #[Groups('matchF')]
    #[Assert\Type(
        type: 'integer',
        message: 'La valeur {{ value }} n est pas valide {{ type }}.',
    )]
    #[Assert\Positive(
        message: 'La valeur doit être positive.',
    )]
    private ?int $prix = null;

    

    #[ORM\Column(nullable: true)]
    #[Assert\Type(
        type: 'integer',
        message: 'La valeur {{ value }} n est pas valide {{ type }}.',
    )]
    #[Assert\Positive(
        message: 'La valeur doit être positive.',
    )]
    private ?int $nbBilletTotal = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(
        type: 'integer',
        message: 'La valeur {{ value }} n est pas valide {{ type }}.',
    )]
    
    private ?int $nbBilletReserve = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Range( min : "today",notInRangeMessage : "The date must be minimum today " )]
    private ?\DateTimeInterface $dateMatch = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureDebM = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heurefinM = null;

    #[ORM\ManyToOne(inversedBy: 'matchFs')]
    #[Assert\NotBlank(message:'Svp entrez le nom de l équipe à domicile!')]
    #[Assert\Length(min:3)]
    private ?Equipe $equipeA = null;

    #[ORM\ManyToOne(inversedBy: 'matchF')]
    #[Assert\NotBlank(message:'Svp entrez le nom de l équipe éxterieure!')]
    #[Assert\Length(min:3)]
    private ?Equipe $equipeB = null;

    #[ORM\ManyToOne(inversedBy: 'matchFs')]
    #[Assert\NotBlank(message:'Svp entrez le nom du stade!')]
    #[Assert\Length(min:3)]
    private ?Stade $stade = null;

    #[ORM\ManyToOne(inversedBy: 'matchFs')]
    #[Assert\NotBlank(message:'Svp entrez le nom du tournoi!')]
    #[Assert\Length(min:3)]
    private ?Tournoi $tournoi = null;

    #[ORM\ManyToOne(inversedBy: 'matchFs')]
    #[Assert\NotBlank(message:'Svp entrez le type du match!')]
    #[Assert\Length(min:3)]
    private ?TypeMatch $typeMatch = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $image2 = null;

    

    

    

   

 
    

    

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

 
    

   

    

    

    
    

    public function getResultatA(): ?int
    {
        return $this->resultatA;
    }

    public function setResultatA(int $resultatA): self
    {
        $this->resultatA = $resultatA;

        return $this;
    }

    public function getResultatB(): ?int
    {
        return $this->resultatB;
    }

    public function setResultatB(int $resultatB): self
    {
        $this->resultatB = $resultatB;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setMatchF($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getMatchF() === $this) {
                $reservation->setMatchF(null);
            }
        }

        return $this;
    }

   

    

    public function __toString()
    {
        return $this->id; 
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    
    public function getNbBilletTotal(): ?int
    {
        return $this->nbBilletTotal;
    }

    public function setNbBilletTotal(?int $nbBilletTotal): self
    {
        $this->nbBilletTotal = $nbBilletTotal;

        return $this;
    }

    public function getNbBilletReserve(): ?int
    {
        return $this->nbBilletReserve;
    }

    public function setNbBilletReserve(?int $nbBilletReserve): self
    {
        $this->nbBilletReserve = $nbBilletReserve;

        return $this;
    }

    public function getDateMatch(): ?\DateTimeInterface
    {
        return $this->dateMatch;
    }

    public function setDateMatch(?\DateTimeInterface $dateMatch): self
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }
    /**
     * @Assert\LessThan(propertyPath="heurefinM")
     */
    public function getHeureDebM(): ?\DateTimeInterface
    {
        return $this->heureDebM;
    }

    public function setHeureDebM(?\DateTimeInterface $heureDebM): self
    {
        $this->heureDebM = $heureDebM;

        return $this;
    }

    public function getHeurefinM(): ?\DateTimeInterface
    {
        return $this->heurefinM;
    }

    public function setHeurefinM(?\DateTimeInterface $heurefinM): self
    {
        $this->heurefinM = $heurefinM;

        return $this;
    }

    public function getEquipeA(): ?Equipe
    {
        return $this->equipeA;
    }

    public function setEquipeA(?Equipe $equipeA): self
    {
        $this->equipeA = $equipeA;

        return $this;
    }

    public function getEquipeB(): ?Equipe
    {
        return $this->equipeB;
    }

    public function setEquipeB(?Equipe $equipeB): self
    {
        $this->equipeB = $equipeB;

        return $this;
    }

    public function getStade(): ?Stade
    {
        return $this->stade;
    }

    public function setStade(?Stade $stade): self
    {
        $this->stade = $stade;

        return $this;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;

        return $this;
    }

    public function getTypeMatch(): ?TypeMatch
    {
        return $this->typeMatch;
    }

    public function setTypeMatch(?TypeMatch $typeMatch): self
    {
        $this->typeMatch = $typeMatch;

        return $this;
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

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    

    



    

  

    
}
