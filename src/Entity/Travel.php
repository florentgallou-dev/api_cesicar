<?php

namespace App\Entity;

use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping\JoinTable;
use App\Repository\TravelRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Elasticsearch\Filter\TermFilter;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\OpenApi\Model;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Travel',
    operations: [
        new GetCollection(
            description: 'Retrieves the collection of Travels',
            uriTemplate: '/travels',
            normalizationContext: ['groups' => 'read:travels']
        ),
        new Get(
            description: 'Retrieves one Travel',
            uriTemplate: '/travel/{id}',
            normalizationContext: ['groups' => 'read:travel'],
        ),
        new Post(
            description: 'Creates a new travel',
            uriTemplate: '/travel',
            denormalizationContext: ['groups' => 'create:travel'],
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Créer un nouveau voyage',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
        new Patch(
            description: 'Update an existing Travel',
            uriTemplate: '/travel/{id}',
            denormalizationContext: ['groups' => 'update:travel'],
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Mettre à jour un voyage',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
        new Delete(
            description: 'Delete a travel and cascade delete all it\s voyagers subscriptions',
            uriTemplate: '/travel/{id}',
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Supprimer un voyage',
                                            security: [['bearerAuth' => []]]
                                        )
        ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    description: 'Resources des trajets proposés par nos conducteurs'
),
ApiFilter(TermFilter::class, properties: ['toCesi, isPublic']),
ApiFilter(DateFilter::class, strategy: DateFilter::PARAMETER_AFTER)]
class Travel
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:travels', 'read:travel', 'read:User'])]
    private ?int $id;

    #[ORM\Column(length: 250)]
    #[Groups(['create:travel', 'read:travel', 'update:travel', 'read:User'])]
    private ?string $name;

    /**
    *   toCesi field is used to tel if travel is to go to CESI or not
    **/
    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $toCesi = false;

    /**
    *   Position field : 
    *   if toCesi = false -> position = back_direction from CESI,
    *   if toCesi = true  -> position = start_position to CESI
    **/
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?array $address;

    /**
    *   Datetime telling when travel starts, use it with travel information to calculate traveltime
    **/
    #[ORM\Column]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?\DateTime $departure_date;

    #[ORM\Column]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?int $number_seats;

    /**
    *   If isPublic = true, travel is shared with everyone,
    *   If isPublic = false, travel is invisible for others,
    *   If isPublic = false, driver can select voyagers manualy
    **/
    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    #[ApiFilter(BooleanFilter::class)]
    private ?bool $isPublic = false;

//Relationships
    #[ORM\ManyToOne(inversedBy: 'travels')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    #[Groups(['read:travels', 'read:travel'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'inscriptions')]
    #[JoinTable(name: 'travels_voyagers')]
    #[Assert\Count(
        max: 6,
        maxMessage: 'Vous ne pouvez pas ajouter plus de {{ limit }} voyageurs'
    )]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private Collection $voyagers;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->voyagers = new ArrayCollection();
    }

    public function __toString(): string
    {
        if($this->isToCesi()){
            return $this->getName().' vers CESI';
        }else{
            return $this->getName().' départ CESI';
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isToCesi(): bool
    {
        return $this->toCesi;
    }
    public function setToCesi(bool $toCesi): self
    {
        $this->toCesi = $toCesi;
        return $this;
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

    public function getDepartureDate(): ?\DateTime
    {
        return $this->departure_date;
    }
    public function setDepartureDate(\DateTime $departure_date): self
    {
        $this->departure_date = $departure_date;
        return $this;
    }

    public function getNumberSeats(): ?int
    {
        return $this->number_seats;
    }
    public function setNumberSeats(int $number_seats): self
    {
        $this->number_seats = $number_seats;
        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }
    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
        return $this;
    }


//Relationships GETTER SETTERS
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getVoyagers(): Collection
    {
        return $this->voyagers;
    }
    public function addVoyager(User $voyager): self
    {
        if (!$this->voyagers->contains($voyager)) {
            $this->voyagers->add($voyager);
        }
        return $this;
    }
    public function removeVoyager(User $voyager): self
    {
        $this->voyagers->removeElement($voyager);
        return $this;
    }

}
