<?php

namespace App\Entity;

use App\Repository\OrganizerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrganizerRepository::class)
 */
class Organizer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $phone;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="organizer")
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     * @Groups("event_read")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="organizer", cascade={"persist", "remove"})
     * 
     * @Groups("organizer_browse")
     * 
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="organizers")
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     * @Groups("artist_read")
     */
    private $artist;

    /**
     * @ORM\Column(type="datetime_immutable")
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="organizers")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     * @Groups("status_read")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     * @Groups("region_read")
     */
    private $region;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->artist = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
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
            $event->addOrganizer($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeOrganizer($this);
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
        if ($user->getOrganizer() !== $this) {
            $user->setOrganizer($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtist(): Collection
    {
        return $this->artist;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artist->contains($artist)) {
            $this->artist[] = $artist;
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        $this->artist->removeElement($artist);

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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

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
