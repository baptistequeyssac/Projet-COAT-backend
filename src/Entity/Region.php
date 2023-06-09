<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"artist_browse"})
     * @Groups({"artist_read"})
     * @Groups({"organizer_browse"})
     * @Groups({"organizer_read"})
     * @Groups({"event_browse"})
     * @Groups({"event_read"})
     * @Groups("region_browse")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * 
     * @Groups({"artist_browse"})
     * @Groups({"artist_read"})
     * @Groups({"organizer_browse"})
     * @Groups({"organizer_read"})
     * @Groups({"event_browse"})
     * @Groups({"event_read"})
     * @Groups("region_browse")
     */
    private $name;

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
}
