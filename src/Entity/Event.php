<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     * @Groups("artist_read")
     * @Groups("artist_browse")
     * @Groups("organizer_read")
     * @Groups("organizer_browse")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     * @Groups("artist_read")
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $address;

    /**
     * @ORM\Column(type="decimal")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("artist_read")
     * @Groups("event_add")
     */
    private $poster;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $info;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $frequency;
    // TODO ajouter un group artist_add ou event_add pour éviter circular ref
    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, inversedBy="events")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     * 
     * 
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("type_read")
     * @Groups("event_add")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Organizer::class, inversedBy="events")
     * 
     * 
     * @Groups("event_read")
     * @Groups("event_browse")
     * @Groups("event_add")
     */
    private $organizer;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("event_add")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("event_browse")
     * @Groups("event_read")
     * @Groups("region_read")
     * @Groups("event_add")
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=Stockage::class, mappedBy="event")
     */
    private $stockages;

    public function __construct()
    {
        $this->artist = new ArrayCollection();
        $this->organizer = new ArrayCollection();
        $this->stockages = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(?string $frequency): self
    {
        $this->frequency = $frequency;

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Organizer>
     */
    public function getOrganizer(): Collection
    {
        return $this->organizer;
    }

    public function addOrganizer(Organizer $organizer): self
    {
        if (!$this->organizer->contains($organizer)) {
            $this->organizer[] = $organizer;
        }

        return $this;
    }

    public function removeOrganizer(Organizer $organizer): self
    {
        $this->organizer->removeElement($organizer);

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
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

    /**
     * @return Collection<int, Stockage>
     */
    public function getStockages(): Collection
    {
        return $this->stockages;
    }

    public function addStockage(Stockage $stockage): self
    {
        if (!$this->stockages->contains($stockage)) {
            $this->stockages[] = $stockage;
            $stockage->setEvent($this);
        }

        return $this;
    }

    public function removeStockage(Stockage $stockage): self
    {
        if ($this->stockages->removeElement($stockage)) {
            // set the owning side to null (unless already changed)
            if ($stockage->getEvent() === $this) {
                $stockage->setEvent(null);
            }
        }

        return $this;
    }
}
