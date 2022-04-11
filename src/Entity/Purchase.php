<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase", uniqueConstraints={@ORM\UniqueConstraint(name="idC_2", columns={"idC", "idP"})}, indexes={@ORM\Index(name="idC", columns={"idC"}), @ORM\Index(name="idP", columns={"idP"})})
 * @ORM\Entity
 */
class Purchase
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpu;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idP", referencedColumnName="idP")
     * })
     */
    private $idp;

    /**
     * @var \Cart
     *
     * @ORM\ManyToOne(targetEntity="Cart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idC", referencedColumnName="idC")
     * })
     */
    private $idc;

    public function getIdpu(): ?int
    {
        return $this->idpu;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIdp(): ?Product
    {
        return $this->idp;
    }

    public function setIdp(?Product $idp): self
    {
        $this->idp = $idp;

        return $this;
    }

    public function getIdc(): ?Cart
    {
        return $this->idc;
    }

    public function setIdc(?Cart $idc): self
    {
        $this->idc = $idc;

        return $this;
    }


}
