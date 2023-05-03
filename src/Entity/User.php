<?php

namespace App\Entity;

use App\Entity\Report;
use App\Entity\Travel;
use App\Entity\Message;
use App\Entity\Inscription;
use App\Entity\Conversation;
use ApiPlatform\Metadata\Patch;
use App\Controller\MeController;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
#[ApiResource(
    operations: [
        new GetCollection(
                uriTemplate: '/me',
                controller: MeController::class,
                normalizationContext: ['groups' => 'read:user'],
                paginationEnabled: false,
                read: true,
                output: false,
        ),
        new Patch(normalizationContext: ['groups' => 'update:user']),
        // new Post(
        //     uriTemplate: '/user',
        //     normalizationContext: ['groups' => 'create:user']
        // ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestamps;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:user', 'create:travel'])]
    private ?int $id;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Votre prénom est nécessaire à votre enregistrement.")]
    #[Assert\Length(
            min: 3, max: 50,
            minMessage: 'Votre prénom doit comporter au minimum 3 caractères.',
            maxMessage: 'Votre prénom ne peux dépasser 50 caractères.'
    )]
    #[Groups(['read:user'])]
    private ?string $first_name;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Votre nom est nécessaire à votre enregistrement.")]
    #[Assert\Length(
            min: 3,
            max: 50,
            minMessage: 'Votre nom doit comporter au minimum 3 caractères.',
            maxMessage: 'Votre nom ne peux dépasser 50 caractères.'
    )]
    #[Groups(['read:user'])]
    private ?string $last_name;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: "Votre genre est nécessaire à votre enregistrement.")]
    #[Assert\Choice(callback: 'getGenders')]
    #[Groups(['read:user'])]
    private ?string $gender;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Votre email est indispensable pour vous identifier.")]
    #[Groups(['read:user'])]
    private ?string $email;
    
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Votre mot de passe est indispensable pour vous identifier.")]
    private ?string $password;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $roles = [];

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:user'])]
    private ?array $position = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:user'])]
    private ?bool $driver = false;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Le type de votre véhicule est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Groups(['read:user'])]
    private ?string $car_type = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Votre plaque d'imatriculation est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Groups(['read:user'])]
    private ?string $car_registration = null;

    #[ORM\Column(nullable: true)]
    #[Assert\When(
        expression: 'this.getDriver() == true',
        constraints: [
            new Assert\NotBlank(message: "Le nombre de places disponible dans votre véhicule est nécessaire si vous êtres un conducteur.")
        ],
    )]
    #[Groups(['read:user'])]
    private ?int $car_nb_places = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deleted_at = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isVerified = false;

    // Relationships
    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Inscription $inscription;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Travel $travel;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Conversation $conversation;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Message $message;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Report $report;

    public function __toString(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['read:travels'])]
    public function getName(): ?string
    {
        return $this->first_name.' '.$this->last_name;
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

    public function getPosition(): ?array
    {
        $position[] = $this->position;
        return $this->position;
    }

    public function setPosition(array $position): self
    {
        $this->position = $position;

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

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(Inscription $inscription): self
    {
        
        // set the owning side of the relation if necessary
        if ($inscription->getUser() !== $this) {
            $inscription->setUser($this);
        }

        $this->inscription = $inscription;

        return $this;
    }

    public function getTravel(): ?Travel
    {
        return $this->travel;
    }

    public function setTravel(Travel $travel): self
    {
        
        // set the owning side of the relation if necessary
        if ($travel->getUser() !== $this) {
            $travel->setUser($this);
        }

        $this->travel = $travel;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(Conversation $conversation): self
    {
        
        // set the owning side of the relation if necessary
        if ($conversation->getUser() !== $this) {
            $conversation->setUser($this);
        }

        $this->conversation = $conversation;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(Message $message): self
    {

        // set the owning side of the relation if necessary
        if ($message->getUser() !== $this) {
            $message->setUser($this);
        }

        $this->message = $message;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(Report $report): self
    {
        
        // set the owning side of the relation if necessary
        if ($report->getUser() !== $this) {
            $report->setUser($this);
        }

        $this->report = $report;

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
}
