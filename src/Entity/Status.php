<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("status_browse")
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * 
     * @Groups("status_browse")
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Organizer::class, mappedBy="status")
     * 
     * @Groups("status_browse")
     * @Groups("organizer_browse")
     * @Groups("organizer_read")
     */
    private $organizers;

    public function __construct()
    {
        $this->organizers = new ArrayCollection();
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
            $organizer->setStatus($this);
        }

        return $this;
    }

    public function removeOrganizer(Organizer $organizer): self
    {
        if ($this->organizers->removeElement($organizer)) {
            // set the owning side to null (unless already changed)
            if ($organizer->getStatus() === $this) {
                $organizer->setStatus(null);
            }
        }

        return $this;
    }
}
