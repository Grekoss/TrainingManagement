<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository")
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rushOf;

    /**
     * @ORM\Column(type="time")
     */
    private $startAt;

    /**
     * @ORM\Column(type="time")
     */
    private $stopAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Enter a position")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isResponsible;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Write the goals")
     */
    private $goals;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull(message="Write your comment")
     */
    private $studentComments;

    /**
     * @ORM\Column(type="integer")
     */
    private $feelRush;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSeen;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentReport", mappedBy="report")
     */
    private $commentReports;

    public function __construct()
    {
        $this->dateAt = new \DateTime();
        $this->isSeen = false;
        $this->commentReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAt(): ?\DateTimeInterface
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeInterface $dateAt): self
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getRushOf(): ?string
    {
        return $this->rushOf;
    }

    public function setRushOf(string $rushOf): self
    {
        $this->rushOf = $rushOf;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getStopAt(): ?\DateTimeInterface
    {
        return $this->stopAt;
    }

    public function setStopAt(\DateTimeInterface $stopAt): self
    {
        $this->stopAt = $stopAt;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIsResponsible(): ?bool
    {
        return $this->isResponsible;
    }

    public function setIsResponsible(bool $isResponsible): self
    {
        $this->isResponsible = $isResponsible;

        return $this;
    }

    public function getGoals(): ?string
    {
        return $this->goals;
    }

    public function setGoals(?string $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    public function getStudentComments(): ?string
    {
        return $this->studentComments;
    }

    public function setStudentComments(string $studentComments): self
    {
        $this->studentComments = $studentComments;

        return $this;
    }

    public function getFeelRush(): ?int
    {
        return $this->feelRush;
    }

    public function setFeelRush(int $feelRush): self
    {
        $this->feelRush = $feelRush;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getIsSeen(): ?bool
    {
        return $this->isSeen;
    }

    public function setIsSeen(bool $isSeen): self
    {
        $this->isSeen = $isSeen;

        return $this;
    }

    /**
     * @return Collection|CommentReport[]
     */
    public function getCommentReports(): Collection
    {
        return $this->commentReports;
    }

    public function addCommentReport(CommentReport $commentReport): self
    {
        if (!$this->commentReports->contains($commentReport)) {
            $this->commentReports[] = $commentReport;
            $commentReport->setReport($this);
        }

        return $this;
    }

    public function removeCommentReport(CommentReport $commentReport): self
    {
        if ($this->commentReports->contains($commentReport)) {
            $this->commentReports->removeElement($commentReport);
            // set the owning side to null (unless already changed)
            if ($commentReport->getReport() === $this) {
                $commentReport->setReport(null);
            }
        }

        return $this;
    }
}
