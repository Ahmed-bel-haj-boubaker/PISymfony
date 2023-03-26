<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups ;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("blog")]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom est obligatoire ")]
    #[Groups("blog")]

    private ?string $titre = null;

   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le nom est obligatoire ")]
    #[Groups("blog")]
    private ?string $descreption = null;

    
    #[ORM\Column(length: 100000)]
    #[Assert\NotBlank(message:"le nom est obligatoire ")]
    #[Groups("blog")]

    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    #[Groups("blog")]
    private ?string $image = "ps.png";

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Commentaire::class )]
    private Collection $commentaire;

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescreption(): ?string
    {
        return $this->descreption;
    }

    public function setDescreption(string $descreption): self
    {
        $this->descreption = $descreption;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getBlog() === $this) {
                $commentaire->setBlog(null);
            }
        }

        return $this;
    }
}
