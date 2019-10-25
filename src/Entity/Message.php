<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sentMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $received;

    /**
     * @ORM\Column(type="datetime")
     */
    private $writeAt;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    public function __construct()
    {
        $this->writeAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceived(): ?User
    {
        return $this->received;
    }

    public function setReceived(?User $received): self
    {
        $this->received = $received;

        return $this;
    }

    public function getWriteAt(): ?\DateTimeInterface
    {
        return $this->writeAt;
    }

    public function setWriteAt(\DateTimeInterface $writeAt): self
    {
        $this->writeAt = $writeAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getInterlocutors(): ?array
    {
        return [$this->getSender(), $this->getReceived()];
    }
}
