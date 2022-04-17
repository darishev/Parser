<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reviews_count;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $created_date;

    /**
     * @ORM\Column(type="date_immutable", nullable=true)
     */
    private $updated_date;

    /**
     * @ORM\ManyToOne(targetEntity="Seller", inversedBy="products")
     */
    private $seller;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
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

    public function getReviewsCount(): ?int
    {
        return $this->reviews_count;
    }

    public function setReviewsCount(?int $reviews_count): self
    {
        $this->reviews_count = $reviews_count;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeImmutable $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeImmutable
    {
        return $this->updated_date;
    }

    public function setUpdatedDate(?\DateTimeImmutable $updated_date): self
    {
        $this->updated_date = $updated_date;

        return $this;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function setSeller(?Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }



    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created_date = new \DateTimeImmutable();

    }

    /**
     * @ORM\PrePersist
     */
    public function setUpdatedValues()
    {
        $this->updated_date = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->created_date;

    }
}
