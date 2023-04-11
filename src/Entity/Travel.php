<?php

namespace App\Entity;

use App\Entity\Trait\Timestamps;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TravelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Travel
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 250)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $start_point = null;

    #[ORM\Column(length: 150)]
    private ?string $end_point = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_datetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_datetime = null;

    #[ORM\Column]
    private ?int $number_seats = null;

    #[ORM\OneToOne(mappedBy: 'travel', cascade: ['persist', 'remove'])]
    private ?Inscription $inscription = null;

    #[ORM\OneToOne(inversedBy: 'travel', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?user $user = null;

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

    public function getStartPoint(): ?string
    {
        return $this->start_point;
    }

    public function setStartPoint(string $start_point): self
    {
        $this->start_point = $start_point;

        return $this;
    }

    public function getEndPoint(): ?string
    {
        return $this->end_point;
    }

    public function setEndPoint(string $end_point): self
    {
        $this->end_point = $end_point;

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(\DateTimeInterface $start_datetime): self
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->end_datetime;
    }

    public function setEndDatetime(\DateTimeInterface $end_datetime): self
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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
