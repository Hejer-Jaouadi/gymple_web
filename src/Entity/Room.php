<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $roomname;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $roomnumber;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $max_nbr;

    /**
     * @ORM\ManyToOne(targetEntity=Gym::class, inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false,name="idgym", referencedColumnName="idg")
     */
    private $idgym;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getRoomname(): ?string
    {
        return $this->roomname;
    }

    public function setRoomname(string $roomname): self
    {
        $this->roomname = $roomname;

        return $this;
    }

    public function getRoomnumber(): ?int
    {
        return $this->roomnumber;
    }

    public function setRoomnumber(int $roomnumber): self
    {
        $this->roomnumber = $roomnumber;

        return $this;
    }

    public function getMaxNbr(): ?int
    {
        return $this->max_nbr;
    }

    public function setMaxNbr(int $max_nbr): self
    {
        $this->max_nbr = $max_nbr;

        return $this;
    }

    public function getIdgym(): ?Gym
    {
        return $this->idgym;
    }

    public function setIdgym(?Gym $idgym): self
    {
        $this->idgym = $idgym;

        return $this;
    }
}
