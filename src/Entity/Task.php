<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    const STATUS_TO_DO = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_IN_REVIEW = 3;
    const STATUS_DONE = 4;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @var TaskType
     * @ORM\ManyToOne(targetEntity="App\Entity\TaskType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assignee;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status = self::STATUS_TO_DO;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __toString()
    {
        return $this->title.' ('.$this->project->getName().')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function getType(): ?TaskType
    {
        return $this->type;
    }

    public function setType(TaskType $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(User $assignee): void
    {
        $this->assignee = $assignee;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
