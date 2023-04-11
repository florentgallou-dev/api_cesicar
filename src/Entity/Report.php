<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ApiResource]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'report', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete:"cascade")]
    private ?user $user = null;

    #[ORM\Column]
    private ?int $id_reportable = null;

    #[ORM\Column(length: 250)]
    private ?string $type_reportable = null;

    #[ORM\Column(length: 250)]
    private ?string $message = null;

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
}
