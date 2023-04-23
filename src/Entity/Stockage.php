<?php

namespace App\Entity;

use App\Repository\StockageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StockageRepository::class)
 */
class Stockage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("user_read")
     * @Groups("stockage_browse")
     * @Groups("stockage_read")
     * @Groups("organizer_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("stockage_browse")
     * @Groups("stockage_read")
     * @Groups("organizer_read")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("stockage_browse")
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("stockage_browse")
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stockages", fetch="EAGER")
     * @Groups("stockage_browse")
     * @Groups("stockage_read")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="stockages")
     * @Groups("stockage_browse")
     * @Groups("stockage_read")
     */
    private $event;

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    
}
