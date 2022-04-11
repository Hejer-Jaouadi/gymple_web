<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations")
 * @ORM\Entity
 */
class Reservations
{
    /**
     * @var int
     *
     * @ORM\Column(name="idr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="coach_name", type="string", length=255, nullable=false)
     */
    private $coachName;

    /**
     * @var string
     *
     * @ORM\Column(name="course_name", type="string", length=255, nullable=false)
     */
    private $courseName;

    /**
     * @var string
     *
     * @ORM\Column(name="reserved_date", type="string", length=255, nullable=false)
     */
    private $reservedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="reserved_time", type="string", length=255, nullable=false)
     */
    private $reservedTime;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getCoachName(): ?string
    {
        return $this->coachName;
    }

    public function setCoachName(string $coachName): self
    {
        $this->coachName = $coachName;

        return $this;
    }

    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    public function setCourseName(string $courseName): self
    {
        $this->courseName = $courseName;

        return $this;
    }

    public function getReservedDate(): ?string
    {
        return $this->reservedDate;
    }

    public function setReservedDate(string $reservedDate): self
    {
        $this->reservedDate = $reservedDate;

        return $this;
    }

    public function getReservedTime(): ?string
    {
        return $this->reservedTime;
    }

    public function setReservedTime(string $reservedTime): self
    {
        $this->reservedTime = $reservedTime;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }


}
