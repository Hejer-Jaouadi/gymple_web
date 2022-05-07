<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GymRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;
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
