<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(nullable: true)]
    private ?int $floorNumber = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CityRegion $cityRegion = null;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Property::class)]
    private Collection $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getFloorNumber(): ?int
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(?int $floorNumber): static
    {
        $this->floorNumber = $floorNumber;

        return $this;
    }

    public function getCityRegion(): ?CityRegion
    {
        return $this->cityRegion;
    }

    public function setCityRegion(?CityRegion $cityRegion): static
    {
        $this->cityRegion = $cityRegion;

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setAddress($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): static
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getAddress() === $this) {
                $property->setAddress(null);
            }
        }

        return $this;
    }
}
