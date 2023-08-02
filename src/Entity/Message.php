<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Elasticsearch\Filter\TermFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\User;
use App\Entity\Conversation;
use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Message',
    operations: [
        new GetCollection(
            description: 'Retrieves the collection of messages',
            uriTemplate: '/messages',
            normalizationContext: ['groups' => 'read:messages']
        ),
        new Get(
            description: 'Retrieves one user Message',
            uriTemplate: '/message/{id}',
            normalizationContext: ['groups' => 'read:message']
        ),
        new Post(
            description: 'Creates a new message',
            uriTemplate: '/message',
            normalizationContext: ['groups' => 'create:message']
        ),
        new Patch(
            description: 'Update an existing message',
            uriTemplate: '/message/{id}',
            normalizationContext: ['groups' => 'update:message']
        ),
        new Delete(
            description: 'Delete a message and cascade delete all it\s voyagers subscriptions',
            uriTemplate: '/message/{id}',
            normalizationContext: ['groups' => 'delete:message']
        ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    description: 'Resources des message proposés par nos conducteurs'
),
   ]
class Message
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message;

//Relationships
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?Conversation $conversation = null;

    public function __construct()
    {
        $this->setCreatedAt();
    }

    public function __toString(): string
    {
        return $this->getUser().' : '.$this->getMessage();
    }

    public function getId(): ?int
    {
        return $this->id;
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

//Relationships GETTER SETTERS
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }
    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;
        return $this;
    }

}
