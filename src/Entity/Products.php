<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 * @Vich\Uploadable
 */
class Products
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $IdP;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $category;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank
     * @Assert\Type(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;



    public function getIdP(): ?int
    {
        return $this->IdP;
    }

    public function setIdP(int $IdP): self
    {
        $this->IdP = $IdP;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function setImageFile( $image = null)
    {
        $this->imageFile = $image;


        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}
