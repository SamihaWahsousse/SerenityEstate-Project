<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isAvailable = null;

    #[ORM\Column]
    private ?int $rooms = null;

    #[ORM\Column(length: 255)]
    private ?string $area = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(nullable: false, type: "datetime_immutable", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $createdAt;


    #[ORM\ManyToOne(inversedBy: 'properties', cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'properties', cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?PropertyType $propertyType = null;

    #[ORM\ManyToOne(inversedBy: 'properties', cascade: ["persist", "remove"])]
    private ?Operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'properties', cascade: ["persist", "remove"])]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Propertyad::class)]
    private Collection $propertyads;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: Contract::class)]
    private Collection $contracts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->propertyads = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): static
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPropertyType(): ?PropertyType
    {
        return $this->propertyType;
    }

    public function setPropertyType(?PropertyType $propertyType): static
    {
        $this->propertyType = $propertyType;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Propertyad>
     */
    public function getPropertyads(): Collection
    {
        return $this->propertyads;
    }

    public function addPropertyad(Propertyad $propertyad): static
    {
        if (!$this->propertyads->contains($propertyad)) {
            $this->propertyads->add($propertyad);
            $propertyad->setProperty($this);
        }

        return $this;
    }

    public function removePropertyad(Propertyad $propertyad): static
    {
        if ($this->propertyads->removeElement($propertyad)) {
            // set the owning side to null (unless already changed)
            if ($propertyad->getProperty() === $this) {
                $propertyad->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setProperty($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getProperty() === $this) {
                $contract->setProperty(null);
            }
        }

        return $this;
    }
}
