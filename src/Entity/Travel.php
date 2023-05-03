<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Inscription;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
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
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Travel',
    operations: [
        new GetCollection(
            uriTemplate: '/travels',
            description: 'Retrieves the collection of Travels',
            normalizationContext: ['groups' => 'read:travels']
        ),
        new Get(
            uriTemplate: '/travel/{id}',
            normalizationContext: ['groups' => 'read:travel']
        ),
        new Post(
            uriTemplate: '/travel',
            normalizationContext: ['groups' => 'create:travel']
        ),
        new Patch(
            uriTemplate: '/travel/{id}',
            normalizationContext: ['groups' => 'update:travel']
        ),
        new Delete(
            uriTemplate: '/travel/{id}',
            normalizationContext: ['groups' => 'delete:travel']
        ),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
    description: 'Resources des trajets proposés par nos conducteurs'
)]
// #[ApiFilter(
//     SearchFilter::class,
//     properties: [
//         "end_point" => "exact"
//     ]
// )]
class Travel
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:travels', 'read:travel'])]
    private ?int $id;

    #[ORM\Column(length: 250)]
    #[Groups(['create:travel', 'update:travel'])]
    private ?string $name;

    /**
    *   toCesi field is used to tel if travel is to go to CESI or not
    **/
    #[ORM\Column(type: 'boolean')]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?bool $toCesi = false;

    /**
    *   Position field : 
    *   if toCesi = false -> position = back_direction from CESI,
    *   if toCesi = true  -> position = start_position to CESI
    **/
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?array $position;

    /**
    *   Datetime telling when travel starts, use it with travel information to calculate traveltime
    **/
    #[ORM\Column]
    #[Groups(['read:travels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?\DateTime $departure_date;

    #[ORM\Column]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?int $number_seats;

//Relationships
    #[ORM\ManyToOne(inversedBy: 'travels')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'inscriptions')]
    #[JoinTable(name: 'travels_voyagers')]
    private Collection $voyagers;

    public function __construct()
    {
        $this->voyagers = new ArrayCollection();
    }

    public function __toString(): string
    {
        if($this->isToCesi()){
            return $this->getName().' : '.json_encode($this->getPosition()).' vers CESI';
        }else{
            return $this->getName().' : CESI vers '.json_encode($this->getPosition());
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
