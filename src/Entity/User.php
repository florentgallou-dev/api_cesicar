<?php

namespace App\Entity;

use App\Entity\Report;
use App\Entity\Travel;
use App\Entity\Message;
use App\Entity\Conversation;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Controller\MeController;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
#[ApiResource(
    security: 'is_granted("ROLE_USER")',
    operations: [
        new Get(
            description: 'Récupérer l\'utilisateur connecté',
            uriTemplate: '/me',
            normalizationContext: ['groups' => ['read:User']],
            controller: MeController::class,
            paginationEnabled: false,
            read: false,
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Récupérer l\'utilisateur connecté',
                                            security: [['bearerAuth' => []]] //for JWT token
                                        )
        ),
        new Patch(
            description: 'Mettre à jour l\'utilisateur connecté',
            uriTemplate: '/me',
            controller: MeController::class,
            routeName: 'patch_user',
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Récupérer l\'utilisateur connecté',
                                            security: [['bearerAuth' => []]] //for JWT token
                                        )
        ),
        new Delete(
            description: 'Delete a user account and all he has by cascade',
            uriTemplate: '/me/{id}',
            controller: MeController::class,
            routeName: 'delete_user',
            security: 'object.email == user.email',
            openapi: new Model\Operation(
                                            summary: 'Supprimer un utilisateur',
                                            security: [['bearerAuth' => []]]
                                        )
        )
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
),
ApiFilter(ExistsFilter::class, properties: ['deleted_at'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamps;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:User', 'create:travel', 'read:consersations'])]
    private ?int $id;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Votre prénom doit comporter au minimum 3 caractères.',
            maxMessage: 'Votre prénom ne peux dépasser 50 caractères.'
    )]
    #[Groups(['read:User'])]
    private ?string $first_name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Votre nom doit comporter au minimum 3 caractères.',
            maxMessage: 'Votre nom ne peux dépasser 50 caractères.'
    )]
    #[Groups(['read:User'])]
    private ?string $last_name = null;

    #[ORM\Column(length: 5, nullable: true)]
    #[Assert\Choice(callback: 'getGenders')]
    #[Groups(['read:User'])]
    private ?string $gender;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Votre email est indispensable pour vous identifier.")]
    #[Groups(['read:User'])]
    //EMAIL has to be public for api security check to work
    public ?string $email;
    
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Votre mot de passe est indispensable pour vous identifier.")]
    private ?string $password;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $roles = [];

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:User'])]
    private ?array $address = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:User'])]
    private ?bool $driver = false;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Le type de votre véhicule est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Groups(['read:travel'])]
    private ?string $car_type = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Votre plaque d'imatriculation est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z]{2}[-][0-9]{3}[-][A-Z]/',
        match: false,
        message: 'Votre immatriculation doit respecter le format AB-123-CD'
    )]
    #[Groups(['read:User', 'read:travel'])]
    private ?string $car_registration = null;

    #[ORM\Column(nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Le nombre de places disponible dans votre véhicule est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Groups(['read:User'])]
    private ?int $car_nb_places = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deleted_at = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isVerified = false;

// Relationships
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Travel::class)]
    #[Groups(['read:User'])]
    private ?Collection $travels = null;

    #[ORM\ManyToMany(targetEntity: Travel::class, mappedBy: 'voyagers')]
    #[Groups(['read:User'])]
    private ?Collection $inscriptions = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conversation::class)]
    #[Groups(['read:User'])]
    private ?Collection $conversations = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class)]
    #[Groups(['read:User'])]
    private ?Collection $messages = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Report::class)]
    private ?Collection $reports = null;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->travels = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    #[Groups(['read:travels'])]
    public function getPublicName(): ?string
    {
        return $this->first_name.'.'.strtoupper(substr($this->last_name, 0,1));
    }
    
    #[Groups(['read:travels', 'read:travel', 'read:consersations', 'read:consersation'])]
    public function getName(): ?string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }
    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }
    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    public static function getGenders()
    {
        return ['homme', 'femme', 'autre'];
    }
    public function getGender(): ?string
    {
        return $this->gender;
    }
    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAddress(): ?array
    {
        $address[] = $this->address;
        return $this->address;
    }
    public function setAddress(array $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getDriver(): ?bool
    {
        return $this->driver;
    }
    public function setDriver(bool $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    public function getCarType(): ?string
    {
        return $this->car_type;
    }
    public function setCarType(?string $car_type): self
    {
        $this->car_type = $car_type;
        return $this;
    }

    public function getCarRegistration(): ?string
    {
        return $this->car_registration;
    }
    public function setCarRegistration(?string $car_registration): self
    {
        $this->car_registration = $car_registration;
        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deleted_at;
    }
    public function setDeletedAt(bool $deleted_at): self
    {
        if ($deleted_at) {
            $this->deleted_at = new \DateTime();
        }
        return $this;
    }

    public function getCarNbPlaces(): ?int
    {
        return $this->car_nb_places;
    }
    public function setCarNbPlaces(?int $car_nb_places): self
    {
        $this->car_nb_places = $car_nb_places;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

//Relationships GETTER SETTERS
    /**
     * @return Collection<int, Travel>
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }
    public function addTravel(Travel $travel): self
    {
        if (!$this->travels->contains($travel)) {
            $this->travels->add($travel);
            $travel->setUser($this);
        }
        return $this;
    }
    public function removeTravel(Travel $travel): self
    {
        if ($this->travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getUser() === $this) {
                $travel->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }
    public function addInscription(Travel $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->addVoyager($this);
        }
        return $this;
    }
    public function removeInscription(Travel $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            $inscription->removeVoyager($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }
    public function addConversation(Travel $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setUser($this);
        }
        return $this;
    }
    public function removeConversation(Travel $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }
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
            $message->setUser($this);
        }
        return $this;
    }
    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }
    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setUser($this);
        }
        return $this;
    }
    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getUser() === $this) {
                $report->setUser(null);
            }
        }
        return $this;
    }

}
