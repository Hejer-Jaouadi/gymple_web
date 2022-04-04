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
     * @Assert\NotBlank(message="the name of the room should not be empty")
     * @ORM\Column(type="string", length=255)
     */
    private $roomname;

    /**
     *@Assert\NotBlank(message="the number of the room should not be empty")
     *@Assert\Positive(message="the number must be positive")
     * @ORM\Column(type="integer")
     */
    private $roomnumber;

    /**
     * @Assert\NotBlank(message="the capacity of the room should not be empty")
     *@Assert\Positive(message="the number must be positive")
     * @ORM\Column(type="integer")
     */
    private $max_nbr;

   /* /**
     * @ORM\ManyToOne(targetEntity=Gym::class)
     * @ORM\JoinColumn(nullable=false)
     */
   /* private $idgym;*/

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

    /*public function getIdgym(): ?gym
    {
        return $this->idgym;
    }

    public function setIdgym(?gym $idgym): self
    {
        $this->idgym = $idgym;

        return $this;
    }*/
}
