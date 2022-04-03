<?php

namespace App\Entity;

use App\Repository\GymRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=GymRepository::class)
 */
class Gym
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idg;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/\d/" , match = true,  message="location must contain a street number")
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @Assert\NotBlank
     *  @Assert\Regex(pattern="/\d/",match=false,message="facilities cannot contain a number")
     * @ORM\Column(type="string", length=255)
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
}
