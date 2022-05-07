<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 *
 * @ORM\Table(name="room", indexes={@ORM\Index(name="idgym", columns={"idgym"})})
 * @ORM\Entity
 */
class Room
{
    /**
     * @var int
     *
     * @ORM\Column(name="idR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="roomName", type="string", length=255, nullable=false)
     */
    private $roomname;

    /**
     * @var int
     *
     * @ORM\Column(name="roomNumber", type="integer", nullable=false)
     */
    private $roomnumber;

    /**
     * @var int
     *
     * @ORM\Column(name="max_nbr", type="integer", nullable=false)
     */
    private $maxNbr;

    /**
     * @var \Gym
     *
     * @ORM\ManyToOne(targetEntity="Gym")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idgym", referencedColumnName="idG")
     * })
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
        return $this->maxNbr;
    }

    public function setMaxNbr(int $maxNbr): self
    {
        $this->maxNbr = $maxNbr;

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
