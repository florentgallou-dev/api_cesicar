<?php

namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column]
    private ?\DateTime $created_at;

    #[ORM\Column]
    private ?\DateTime $updated_at;

    #[ORM\PrePersist]
    public function setCreatedAt()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt()
    {
        $this->updated_at = new \DateTime();
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updated_at;
    }

}