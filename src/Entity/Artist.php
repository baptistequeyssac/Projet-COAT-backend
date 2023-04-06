<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Artist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $firstName;

    /**
     * @ORM\Column(type="date")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $phone;
   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $address;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="artists")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     * @Groups("category_read")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="artist")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     * @Groups("event_read")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="artist", cascade={"persist", "remove"})
     * 
     * @Groups("artist_browse")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Organizer::class, mappedBy="artist")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     * @Groups("organizer_read")
     */
    private $organizers;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("artist_browse")
     * @Groups("artist_read")
     * @Groups("region_read")
     * 
     */
    private $region;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->organizers = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtDefaultValue(): void
    {
        // automation of createdAt's date
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        // automation of updateAt's date
        $this->updatedAt = new \DateTime();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
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

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
   
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
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
            $this->events[] = $event;
            $event->addArtist($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeArtist($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getArtist() !== $this) {
            $user->setArtist($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Organizer>
     */
    public function getOrganizers(): Collection
    {
        return $this->organizers;
    }

    public function addOrganizer(Organizer $organizer): self
    {
        if (!$this->organizers->contains($organizer)) {
            $this->organizers[] = $organizer;
            $organizer->addArtist($this);
        }

        return $this;
    }

    public function removeOrganizer(Organizer $organizer): self
    {
        if ($this->organizers->removeElement($organizer)) {
            $organizer->removeArtist($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
