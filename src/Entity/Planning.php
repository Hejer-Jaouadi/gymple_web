<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanningRepository::class)
 */
class Planning
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=courses::class, mappedBy="address", orphanRemoval=true)
     */
    private $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(courses $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setAddress($this);
        }

        return $this;
    }

    public function removeCourse(courses $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getAddress() === $this) {
                $course->setAddress(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->address;
    }

}
