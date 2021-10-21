<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="category")
     */
    private $courseList;

    public function __construct()
    {
        $this->courseList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourseList(): Collection
    {
        return $this->courseList;
    }

    public function addCourseList(Course $courseList): self
    {
        if (!$this->courseList->contains($courseList)) {
            $this->courseList[] = $courseList;
            $courseList->setCategory($this);
        }

        return $this;
    }

    public function removeCourseList(Course $courseList): self
    {
        if ($this->courseList->removeElement($courseList)) {
            // set the owning side to null (unless already changed)
            if ($courseList->getCategory() === $this) {
                $courseList->setCategory(null);
            }
        }

        return $this;
    }
}
