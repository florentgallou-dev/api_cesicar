<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InformationRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InformationRepository::class)]
// #[ApiResource(
//     operations: [
//         new Get(
//             uriTemplate: '/information',
//             normalizationContext: ['groups' => 'read:information']
//         )
//     ],
//     order: ['id' => 'ASC'],
//     paginationEnabled: false,
// )]
class Information
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:information'])]
    private ?int $id;

    #[ORM\Column(length: 150)]
    #[Assert\Length(
        min: 3, max: 150,
        minMessage: 'Le nom du CESI doit comporter au minimum 3 caractères.',
        maxMessage: 'Le nom du CESI ne peux dépasser 150 caractères.'
    )]
    #[Groups(['read:information'])]
    private ?string $cesi_name;

    #[ORM\Column(type: 'json')]
    #[Groups(['read:travels', 'read:travel'])]
    private array $cesi_position = [];

    #[ORM\Column(length: 255)]
    #[Groups(['read:information'])]
    private ?string $contact_email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCesiName(): ?string
    {
        return $this->cesi_name;
    }
    public function setCesiName(string $cesi_name): self
    {
        $this->cesi_name = $cesi_name;
        return $this;
    }

    public function getCesiPosition(): ?array
    {
        $cesi_position[] = $this->cesi_position;
        return $this->cesi_position;
    }
    public function setCesiPosition(array $cesi_position): self
    {
        $this->cesi_position = $cesi_position;
        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }
    public function setContactEmail(string $contact_email): self
    {
        $this->contact_email = $contact_email;
        return $this;
    }
}
