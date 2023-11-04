<?php

namespace App\Entity;

use App\Repository\PropertyadRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PropertyadRepository::class)]
class Propertyad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $fees = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    private ?int $guarantee = null;

    // #[ORM\ManyToOne(inversedBy: 'propertyads')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Property $property = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\OneToOne(mappedBy: 'ad', cascade: ['persist'])]
    private ?Property $propertyRef = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->isActive = true;
    }

    #[ORM\PrePersist]
    public function setUpdatedAtValue(): void
     {
            $this->updatedAt = new \DateTimeImmutable();
            
     }
        
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getFees(): ?int
    {
        return $this->fees;
    }

    public function setFees(?int $fees): static
    {
        $this->fees = $fees;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getGuarantee(): ?int
    {
        return $this->guarantee;
    }

    public function setGuarantee(?int $guarantee): static
    {
        $this->guarantee = $guarantee;

        return $this;
    }

    // public function getProperty(): ?Property
    // {
    //     return $this->property;
    // }

    // public function setProperty(?Property $property): static
    // {
    //     $this->property = $property;

    //     return $this;
    // }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    

    public function getPropertyRef(): ?Property
    {
        return $this->propertyRef;
    }

    public function setPropertyRef(?Property $propertyRef): static
    {
        // unset the owning side of the relation if necessary
        if ($propertyRef === null && $this->propertyRef !== null) {
            $this->propertyRef->setAd(null);
        }

        // set the owning side of the relation if necessary
        if ($propertyRef !== null && $propertyRef->getAd() !== $this) {
            $propertyRef->setAd($this);
        }

        $this->propertyRef = $propertyRef;

        return $this;
    }

    
}