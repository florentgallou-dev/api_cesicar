<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Message;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Elasticsearch\Filter\TermFilter;
use Doctrine\Common\Collections\ArrayCollection;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Consersation',
    operations: [
        new GetCollection(
            description: 'Retrieves the collection of conversations',
            uriTemplate: '/consersations',
            normalizationContext: ['groups' => 'read:consersations'],
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Récupérer les conversations filtrables',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
        new Get(
            description: 'Retrieves one consersation',
            uriTemplate: '/consersation/{id}',
            normalizationContext: ['groups' => ['read:consersations', 'read:consersation']],
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Récupérer une conversation et ses messages',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
        new Post(
            description: 'Creates a new consersation',
            uriTemplate: '/consersation',
            denormalizationContext: ['groups' => 'create:consersation'],
            security: 'is_granted("ROLE_USER")',
            // openapi: new Model\Operation(
            //                                 summary: 'Créer une nouvelle conversation',
            //                                 security: [['bearerAuth' => []]],
            //                                 requestBody: new Model\RequestBody(
            //                                     content: new \ArrayObject([
            //                                         'application/json' => [
            //                                             'schema' => [
            //                                                 'type' => 'object', 
            //                                                 'properties' => [
            //                                                     'subject' => ['type' => 'string']
            //                                                 ]
            //                                             ], 
            //                                             'example' => [
            //                                                 'subject' => 'Ma conversation'
            //                                             ]
            //                                         ]
            //                                     ])
            //                                 )
            //                             )
        ),
        new Delete(
            description: 'Delete a consersation and cascade delete all it\s messages',
            uriTemplate: '/consersation/{id}',
            security: 'object.user == user',
            openapi: new Model\Operation(
                                            summary: 'Supprimer une conversation',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    description: 'Resources des consersations proposés par nos conducteurs'
),
ApiFilter(OrderFilter::class, properties: ['updated_at']),
ApiFilter(SearchFilter::class, properties: [
    'user.first_name' => 'partial',
    'user.last_name' => 'partial',
])
]
class Conversation
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:consersations'])]
    private ?int $id;

    #[ORM\Column(length: 150)]
    #[Groups(['read:consersations', 'create:consersation'])]
    private ?string $subject;

//Relationships
    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    #[Groups(['read:consersations'])]
    //USER has to be public for api security check to work
    public ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class)]
    #[Groups(['read:consersation'])]
    private Collection $messages;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->messages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getUser().' : '.$this->getSubject();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }
        return $this;
    }
    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }
        return $this;
    }

}
