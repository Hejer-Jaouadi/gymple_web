<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CoursesRepository::class)
 */
class Courses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="date can't be null")
     * 
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotNull(message="starting time can't be null")
     */
    private $start_time;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotNull(message="ending time can't be null")
     */
    private $end_time;

    /**
     * 
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="the field 'spots' cannot be empty")
     * @Assert\Range(min=1)
     */
    private $nbr;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="please specify a category, the category field cannot be empty")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Planning::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="please specify an address, the address field cannot be empty") 
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="please specify a trainer, the trainer field cannot be empty")
     */
    private $trainer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(?\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getNbr(): ?int
    {
        return $this->nbr;
    }

    public function setNbr(int $nbr): self
    {
        $this->nbr = $nbr;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAddress(): ?Planning
    {
        return $this->address;
    }

    public function setAddress(?Planning $address): self
    {
        $this->address = $address;

        return $this;
    }


    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    
}
