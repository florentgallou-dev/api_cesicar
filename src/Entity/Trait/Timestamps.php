<?php

namespace App\Entity\Trait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Timestamps
{
    #[ORM\Column]
    private ?\DateTime $created_at;

    #[ORM\Column]
    #[Groups(['read:consersation', 'read:travels'])]
    private ?\DateTime $updated_at;

    public function setCreatedAt()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt()
    {
        $this->updated_at = new \DateTime();
    }
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

}