<?php

namespace App\EventListener;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Report::class)]
class MessageEntityListener {

    public function __construct(private Security $security)
    {}

    public function prePersist(Message $message, LifecycleEventArgs $event) :void
    {
        if($this->security->getUser()){
            $message->setUser($this->security->getUser());
        }
    }

}