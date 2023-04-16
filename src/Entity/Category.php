<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"artist_browse"})
     * @Groups({"artist_read"})     
     * @Groups("category_browse")
     * 
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups({"artist_browse"})
     * @Groups({"artist_read"}) 
     * @Groups("category_browse")
     * 
     * 
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, mappedBy="category")
     * 
     * @Groups("category_browse")
     * 
     */
    private $artists;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
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
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->addCategory($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeCategory($this);
        }

        return $this;
    }
}
