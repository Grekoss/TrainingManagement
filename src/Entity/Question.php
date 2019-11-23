<?php

namespace App\Entity;

use App\Service\Slugger;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prop1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prop2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prop3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prop4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prop5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prop6;

    public function __toString()
    {
        return $this->getQuestion();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getProp1(): ?string
    {
        return $this->prop1;
    }

    public function setProp1(string $prop1): self
    {
        $this->prop1 = $prop1;

        return $this;
    }

    public function getProp2(): ?string
    {
        return $this->prop2;
    }

    public function setProp2(string $prop2): self
    {
        $this->prop2 = $prop2;

        return $this;
    }

    public function getProp3(): ?string
    {
        return $this->prop3;
    }

    public function setProp3(string $prop3): self
    {
        $this->prop3 = $prop3;

        return $this;
    }

    public function getProp4(): ?string
    {
        return $this->prop4;
    }

    public function setProp4(string $prop4): self
    {
        $this->prop4 = $prop4;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSlug()
    {
        $slug = new Slugger(false);

        return $slug->slugify($this->getQuestion());
    }

    public function getProp5(): ?string
    {
        return $this->prop5;
    }

    public function setProp5(?string $prop5): self
    {
        $this->prop5 = $prop5;

        return $this;
    }

    public function getProp6(): ?string
    {
        return $this->prop6;
    }

    public function setProp6(?string $prop6): self
    {
        $this->prop6 = $prop6;

        return $this;
    }
}
