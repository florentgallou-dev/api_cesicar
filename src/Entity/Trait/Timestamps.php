<?php

namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    #[ORM\Column]
    private ?\DateTime $created_at;

    #[ORM\Column]
    private ?\DateTime $updated_at;

    public function __construct()
    {
        $this->setCreatedAt();
    }

    public function setCreatedAt()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setUpdatedAt()
    {
        $this->updated_at = new \DateTime();
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

}