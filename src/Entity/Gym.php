<?php

namespace App\Entity;

use App\Repository\GymRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/\d/" , match = false,  message="facilites must not contain a number")
     */
    private $facilities;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="idgym", orphanRemoval=true)
     */
    private $rooms;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setIdgym($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getIdgym() === $this) {
                $room->setIdgym(null);
            }
        }

        return $this;

    }
}
