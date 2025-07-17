<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'integer')]
    private ?int $stockXS = null;

    #[ORM\Column(type: 'integer')]
    private ?int $stockS = null;

    #[ORM\Column(type: 'integer')]
    private ?int $stockM = null;

    #[ORM\Column(type: 'integer')]
    private ?int $stockL = null;

    #[ORM\Column(type: 'integer')]
    private ?int $stockXL = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }
    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }
    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeInterface { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
    public function getStockXS(): ?int { return $this->stockXS; }
    public function setStockXS(int $stockXS): self { $this->stockXS = $stockXS; return $this; }
    public function getStockS(): ?int { return $this->stockS; }
    public function setStockS(int $stockS): self { $this->stockS = $stockS; return $this; }
    public function getStockM(): ?int { return $this->stockM; }
    public function setStockM(int $stockM): self { $this->stockM = $stockM; return $this; }
    public function getStockL(): ?int { return $this->stockL; }
    public function setStockL(int $stockL): self { $this->stockL = $stockL; return $this; }
    public function getStockXL(): ?int { return $this->stockXL; }
    public function setStockXL(int $stockXL): self { $this->stockXL = $stockXL; return $this; }
}
