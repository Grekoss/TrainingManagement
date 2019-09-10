<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 */
class Result
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
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="array")
     */
    private $responses = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function __construct()
    {
        $this->student = new ArrayCollection();
        $this->quiz = new ArrayCollection();
        $this->dateAt = new \DateTime();
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getResponses(): ?array
    {
        return $this->responses;
    }

    public function setResponses(array $responses): self
    {
        $this->responses = $responses;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

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
}
