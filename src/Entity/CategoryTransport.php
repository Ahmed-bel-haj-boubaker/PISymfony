<?php

namespace App\Entity;

use App\Repository\CategoryTransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryTransportRepository::class)]
class CategoryTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\Length(
        min: 2,
        minMessage: 'Your transport type0 must be at least {{ limit }} characters long',
       )]
    #[ORM\Column(length: 255)]
    private ?string $typetransport = null;

   
    #[ORM\OneToMany(targetEntity: Transport::class, mappedBy: 'categoryTransport')]
    private Collection $transports;
    public function __construct()
    {
        $this->transports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString() {
        return $this->typetransport;
    }
    public function getTypetransport(): ?string
    {
        return $this->typetransport;
    }

    public function setTypetransport(string $typetransport): self
    {
        $this->typetransport = $typetransport;

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
            $transport->setCategoryTransport($this);
        }

        return $this;
    }

    public function removeTransport(Transport $transport): self
    {
        if ($this->transports->removeElement($transport)) {
            // set the owning side to null (unless already changed)
            if ($transport->getCategoryTransport() === $this) {
                $transport->setCategoryTransport(null);
            }
        }

        return $this;
    }
}
