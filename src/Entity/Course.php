<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\ManyToMany(targetEntity=Teacher::class, mappedBy="course")
     */
    private $teacherList;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, mappedBy="course")
     */
    private $studentList;

    public function __construct()
    {
        $this->teacherList = new ArrayCollection();
        $this->studentList = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeacherList(): Collection
    {
        return $this->teacherList;
    }

    public function addTeacherList(Teacher $teacherList): self
    {
        if (!$this->teacherList->contains($teacherList)) {
            $this->teacherList[] = $teacherList;
            $teacherList->addCourse($this);
        }

        return $this;
    }

    public function removeTeacherList(Teacher $teacherList): self
    {
        if ($this->teacherList->removeElement($teacherList)) {
            $teacherList->removeCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudentList(): Collection
    {
        return $this->studentList;
    }

    public function addStudentList(Student $studentList): self
    {
        if (!$this->studentList->contains($studentList)) {
            $this->studentList[] = $studentList;
            $studentList->addCourse($this);
        }

        return $this;
    }

    public function removeStudentList(Student $studentList): self
    {
        if ($this->studentList->removeElement($studentList)) {
            $studentList->removeCourse($this);
        }

        return $this;
    }
}
