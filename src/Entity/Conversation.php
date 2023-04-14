<?php

namespace App\Entity;

use App\Entity\Trait\Timestamps;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConversationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Conversation
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\OneToOne(inversedBy: 'conversation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?User $user;

    #[ORM\Column(length: 150)]
    private ?string $subject;

    #[ORM\OneToOne(mappedBy: 'conversation', cascade: ['persist', 'remove'])]
    private ?Message $message;

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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): self
    {
        // set the owning side of the relation if necessary
        if ($message->getConversation() !== $this) {
            $message->setConversation($this);
        }

        $this->message = $message;

        return $this;
    }
}
