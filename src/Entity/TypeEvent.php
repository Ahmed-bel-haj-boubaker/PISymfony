<?php

namespace App\Entity;

use App\Repository\TypeEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;

#[ORM\Entity(repositoryClass: TypeEventRepository::class)]
class TypeEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("event")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de l'évènement est obligatoire")]
    #[Assert\Length(min: 3 ,minMessage: "Le type de l'évènement contient moins de 3 charactères")]
    #[Groups("event")]
    private ?string $NomType = null;

    #[ORM\OneToMany(mappedBy: 'typeEvent', targetEntity: Event::class)]
    private Collection $events;

   
   
    
    
  
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->NomType;
    }

    public function setNomType(string $NomType): self
    {
        $this->NomType = $NomType;

        return $this;
    }

  

 

  
    public function __toString()
    {
        return $this->NomType; 
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setTypeEvent($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getTypeEvent() === $this) {
                $event->setTypeEvent(null);
            }
        }

        return $this;
    }

    
}
