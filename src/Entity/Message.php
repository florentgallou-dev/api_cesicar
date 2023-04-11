<?php

namespace App\Entity;

use App\Entity\Trait\Timestamps;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Message
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'message', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?user $user = null;

    #[ORM\OneToOne(inversedBy: 'message', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?conversation $conversation = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConversation(): ?conversation
    {
        return $this->conversation;
    }

    public function setConversation(conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
