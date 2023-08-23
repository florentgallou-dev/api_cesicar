<?php

namespace App\EventListener;

use App\Entity\Travel;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Travel::class)]
class TravelEntityListener {

    public function __construct(private Security $security)
    {}

    public function prePersist(Travel $travel, LifecycleEventArgs $event) :void
    {
        if($this->security->getUser()){
            $travel->setUser($this->security->getUser());
        }
    }

}