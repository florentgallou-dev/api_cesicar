<?php

namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at;

    #[ORM\PrePersist]
    public function createdAt()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function updatedAt()
    {
        $this->updated_at = new \DateTime();
    }

}