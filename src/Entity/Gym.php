<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gym
 *
 * @ORM\Table(name="gym")
 * @ORM\Entity
 */
class Gym
{
    /**
     * @var int
     *
     * @ORM\Column(name="idG", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idg;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="facilities", type="string", length=255, nullable=false)
     */
    private $facilities;

    public function getIdg(): ?int
    {
        return $this->idg;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getFacilities(): ?string
    {
        return $this->facilities;
    }

    public function setFacilities(string $facilities): self
    {
        $this->facilities = $facilities;

        return $this;
    }
    public function __toString() {
        return $this->location;
    }


}
