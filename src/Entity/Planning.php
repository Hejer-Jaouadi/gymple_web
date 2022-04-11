<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning", indexes={@ORM\Index(name="gym", columns={"gym"})})
 * @ORM\Entity
 */
class Planning
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="start_date", type="integer", nullable=false)
     */
    private $startDate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="end_date", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $endDate = NULL;

    /**
     * @var \Gym
     *
     * @ORM\ManyToOne(targetEntity="Gym")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gym", referencedColumnName="idG")
     * })
     */
    private $gym;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?int
    {
        return $this->startDate;
    }

    public function setStartDate(int $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?int
    {
        return $this->endDate;
    }

    public function setEndDate(?int $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getGym(): ?Gym
    {
        return $this->gym;
    }

    public function setGym(?Gym $gym): self
    {
        $this->gym = $gym;

        return $this;
    }


}
