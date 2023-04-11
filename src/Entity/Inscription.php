<?php

namespace App\Entity;

use App\Entity\Trait\Timestamps;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource]
class Inscription
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'inscription', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?user $user = null;

    #[ORM\OneToOne(inversedBy: 'inscription', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?travel $travel = null;

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

    public function getTravel(): ?travel
    {
        return $this->travel;
    }

    public function setTravel(travel $travel): self
    {
        $this->travel = $travel;

        return $this;
    }
}
