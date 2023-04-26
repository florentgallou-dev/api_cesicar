<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Inscription;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\TravelRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'read:ontravels']),
        new Get(normalizationContext: ['groups' => 'read:travel']),
        new Post(normalizationContext: ['groups' => 'create:travel']),
        new Patch(normalizationContext: ['groups' => 'update:travel']),
        new Delete(normalizationContext: ['groups' => 'delete:travel']),
    ],
    order: ['id' => 'ASC'],
    paginationEnabled: false,
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
    #[Groups(['read:ontravels', 'read:travel'])]
    private ?int $id;

    #[ORM\Column(length: 250)]
    #[Groups(['create:travel', 'update:travel'])]
    private ?string $name;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:ontravels', 'read:travel', 'create:travel', 'update:travel'])]
    private ?array $start_point;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?array $end_point;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?\DateTime $start_datetime;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?\DateTime $end_datetime;

    #[ORM\Column]
    #[Groups(['read:travel', 'create:travel', 'update:travel'])]
    private ?int $number_seats;

    #[ORM\OneToOne(mappedBy: 'travel', cascade: ['persist', 'remove'])]
    #[Groups(['read:travel'])]
    private ?Inscription $inscription = null;

    #[ORM\OneToOne(inversedBy: 'travel', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    #[Groups(['read:ontravels', 'read:travel', 'create:travel'])]
    private ?User $user;

    public function __toString(): string
    {
        return $this->getName().' : '.$this->getStartPoint().' vers '.$this->getEndPoint();
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

    public function getStartPoint(): ?array
    {
        $start_point[] = $this->start_point;
        return $this->start_point;
    }

    public function setStartPoint(array $start_point): self
    {
        $this->start_point = $start_point;

        return $this;
    }

    public function getEndPoint(): ?array
    {
        $end_point[] = $this->end_point;
        return $this->end_point;
    }

    public function setEndPoint(array $end_point): self
    {
        $this->end_point = $end_point;

        return $this;
    }

    public function getStartDatetime(): ?\DateTime
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(\DateTime $start_datetime): self
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTime
    {
        return $this->end_datetime;
    }

    public function setEndDatetime(\DateTime $end_datetime): self
    {
        $this->end_datetime = $end_datetime;

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

    public function getInscription(): ?Inscription
    {
        return $this->inscription;
    }

    public function setInscription(Inscription $inscription): self
    {
        // set the owning side of the relation if necessary
        if ($inscription->getTravel() !== $this) {
            $inscription->setTravel($this);
        }

        $this->inscription = $inscription;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
