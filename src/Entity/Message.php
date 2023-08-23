<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Conversation;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MessageRepository;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Message',
    operations: [
        new Get(
            description: 'Retrieves one message',
            uriTemplate: '/message/{id}',
            normalizationContext: ['groups' => ['read:message']],
            security: 'object.user == user',
            openapi: new Model\Operation(
                                            summary: 'Récupérer un message',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
        new Post(
            description: 'Creates a message',
            uriTemplate: '/message',
            denormalizationContext: ['groups' => 'create:message'],
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Créer un message',
                                            security: [['bearerAuth' => []]],
                                            requestBody: new Model\RequestBody(
                                                content: new \ArrayObject([
                                                    'application/json' => [
                                                        'schema' => [
                                                            'type' => 'object', 
                                                            'properties' => [
                                                                'message' => ['type' => 'string'],
                                                                'conversation' => ['type' => 'string']
                                                            ]
                                                        ], 
                                                        'example' => [
                                                            'message' => 'Mon message',
                                                            'conversation' => '/api/consersation/1'
                                                        ]
                                                    ]
                                                ])
                                            )
                                        )
        ),
        new Delete(
            description: 'Delete a message',
            uriTemplate: '/message/{id}',
            security: 'object.user == user',
            openapi: new Model\Operation(
                                            summary: 'Supprimer un message',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    description: 'Resources des messages des conversations des conducteurs'
),
ApiFilter(OrderFilter::class, properties: ['updated_at']),
ApiFilter(SearchFilter::class, properties: [
    'conversation.subject' => 'partial'
])
]
class Message
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:message'])]
    private ?int $id;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:message', 'read:consersation', 'create:message'])]
    private ?string $message;
    
//Relationships
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    #[Groups(['read:consersation'])]
    //USER has to be public for api security check to work
    public ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    #[Groups(['read:message', 'create:message'])]
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
