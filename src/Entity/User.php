<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface;
use symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column, Groups('user')]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

    #[ORM\Column(nullable: true), Groups('user')]
    private ?string $password = null;

    #[ORM\Column(nullable: true), Groups('user')]
    private ?string $token = null;
/**
 * @Assert\Email(message="The email '{{ value }}' is not a valid email address.")
 */
    #[ORM\Column(length: 255,nullable: true), Groups('user')]
    private ?string $email = null;

    #[ORM\OneToMany(targetEntity:Commentaire::class, mappedBy: 'user')]

    private Collection $commentaire;

    #[ORM\OneToMany(targetEntity:Reservation::class, mappedBy: 'user')]
    private Collection $reservation;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $image = null;


    #[ORM\OneToMany(targetEntity:Reclamation::class, mappedBy: 'user')]

    private Collection $reclamation;

    #[ORM\Column(nullable: true), Groups('user')]
    private ?int $phone = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE), Groups('user')]
    private ?\DateTimeInterface $dateJoin = null;
 
    #[ORM\Column(name: 'googleId', type: 'integer', length: 255, nullable: true), Groups('user')]
    private ?string $googleId = null;


    #[ORM\Column(name: 'facebookId', type: 'integer', length: 255, nullable: true), Groups('user')]
    private ?string $facebookId = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EventLike::class)]
    private Collection $eventLikes;
    // /**
    //  * @ORM\OneToOne(targetEntity=ConfirmToken::class, cascade={"persist", "remove"})
    //  * @ORM\JoinColumn(onDelete="SET NULL")
    //  */
    // private $token;

   
    #[ORM\Column(nullable: true), Groups('user')]
    private ?bool $banned = null;

    #[ORM\Column(type: 'boolean', nullable:true), Groups('user')]
    private $isVerified = false;


  #[Assert\Length(
     min: 2,
       max: 20,
     minMessage: 'Your first name must be at least {{ limit }} characters long',
       maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255, nullable:true), Groups('user')]
    private ?string $firstName = null;

    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your second name must be at least {{ limit }} characters long',
        maxMessage: 'Your second name cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255, nullable:true), Groups('user')]
    private ?string $secondName = null;
    
  
    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
        $this->reservation = new ArrayCollection();
        $this->reclamation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function serialize() {
        return serialize($this->id);
        }
    
        public function unserialize($data) {
        $this->id = unserialize($data);
        }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function setUsername(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

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
     * @return Collection<int, reclamation>
     */
    public function getReclamation(): Collection
    {
        return $this->reclamation;
    }

    public function addReclamation(reclamation $reclamation): self
    {
        if (!$this->reclamation->contains($reclamation)) {
            $this->reclamation->add($reclamation);
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(reclamation $reclamation): self
    {
        if ($this->reclamation->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDateJoin(): ?\DateTimeInterface
    {
        return $this->dateJoin;
    }

    public function setDateJoin(\DateTimeInterface $dateJoin): self
    {
        $this->dateJoin = $dateJoin;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(string $secondName): self
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get the value of googleId
     */ 
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set the value of googleId
     *
     * @return  self
     */ 
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get the value of facebookId
     */ 
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set the value of facebookId
     *
     * @return  self
     */ 
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

   



    /**
     * Get the value of eventLikes
     */ 
    public function getEventLikes()
    {
        return $this->eventLikes;
    }

    /**
     * Set the value of eventLikes
     *
     * @return  self
     */ 
    public function setEventLikes($eventLikes)
    {
        $this->eventLikes = $eventLikes;

        return $this;
    }
}
