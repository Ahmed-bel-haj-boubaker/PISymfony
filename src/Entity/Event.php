<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: EventRepository::class)]
#[Vich\Uploadable]
class Event
{
    
    #[ORM\Id]
    #[Groups("event")]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("event")]
    #[Assert\NotBlank(message: "Le nom l'évènement est obligatoire")]
    #[Assert\Length(min: 3 ,minMessage: "Le nom de l'évènement contient moins de 3 charactères")]
    
    private ?string $nomEvent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("event")]
    #[Assert\Range( min : "today",notInRangeMessage : "The date must be minimum today " )]
    
    private ?\DateTimeInterface $dateEvent = null;
    #[Groups("event")]
    #[ORM\Column(type: Types::TIME_MUTABLE)]
   
    private ?\DateTimeInterface $heureDeb = null;
    #[Groups("event")]
    #[ORM\Column(type: Types::TIME_MUTABLE)]
   
    private ?\DateTimeInterface $heureFin = null;
    #[Groups("event")]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La localisation de l'évènement est obligatoire")]
    #[Assert\Length(min: 3 ,minMessage: "La localisation de l'évènement contient moins de 3 charactères")]
    
    private ?string $localisation = null;


    #[ORM\Column(length: 255)]
    #[Groups("event")]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $rating = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?TypeEvent $typeEvent = null;

    #[ORM\Column(length: 255)]
    private ?string $videourl = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventLike::class)]
    private Collection $eventLikes;

    

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->eventLikes = new ArrayCollection();
    }

  

  
    






   

    



  





    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): self
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getHeureDeb(): ?\DateTimeInterface
    {
        return $this->heureDeb;
    }

    public function setHeureDeb(\DateTimeInterface $heureDeb): self
    {
        $this->heureDeb = $heureDeb;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getTypeEvent(): ?TypeEvent
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(?TypeEvent $typeEvent): self
    {
        $this->typeEvent = $typeEvent;

        return $this;
    }


    public function __toString()
    {
        return $this->nomEvent; 
    }

    public function getVideourl(): ?string
    {
        return $this->videourl;
    }

    public function setVideourl(string $videourl): self
    {
        $this->videourl = $videourl;

        return $this;
    }

    /**
     * @return Collection<int, EventLike>
     */
    public function getEventLikes(): Collection
    {
        return $this->eventLikes;
    }

    public function addEventLike(EventLike $eventLike): self
    {
        if (!$this->eventLikes->contains($eventLike)) {
            $this->eventLikes->add($eventLike);
            $eventLike->setEvent($this);
        }

        return $this;
    }

    public function removeEventLike(EventLike $eventLike): self
    {
        if ($this->eventLikes->removeElement($eventLike)) {
            // set the owning side to null (unless already changed)
            if ($eventLike->getEvent() === $this) {
                $eventLike->setEvent(null);
            }
        }

        return $this;
    }

   

    

}
