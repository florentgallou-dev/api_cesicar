<?php

namespace App\Entity;

use App\Entity\User;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReportRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    shortName: 'Report',
    operations: [
        new GetCollection(),
        new Post(
            description: 'Créer un rapport',
            uriTemplate: '/report',
            security: 'is_granted("ROLE_USER")',
            openapi: new Model\Operation(
                                            summary: 'Créer un nouveau rapport',
                                            security: [['bearerAuth' => []]]
                                        )
        )
    ],
    paginationEnabled: false,
    description: 'Resources des rapports sur les contenus du site'
)]
class Report
{

    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    private ?int $id_reportable;

    #[ORM\Column(length: 250)]
    private ?string $type_reportable;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message;

//Relationships 
    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?User $user = null;

    public function __construct()
    {
        $this->setCreatedAt();
    }

    public function __toString(): string
    {
        return $this->getUser().' : '.$this->getTypeReportable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReportable(): ?int
    {
        return $this->id_reportable;
    }
    public function setIdReportable(int $id_reportable): self
    {
        $this->id_reportable = $id_reportable;
        return $this;
    }

    public function getTypeReportable(): ?string
    {
        return $this->type_reportable;
    }
    public function setTypeReportable(string $type_reportable): self
    {
        $this->type_reportable = $type_reportable;
        return $this;
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

}
