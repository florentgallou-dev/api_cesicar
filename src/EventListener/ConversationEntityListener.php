<?php

namespace App\EventListener;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Conversation::class)]
class ConversationEntityListener {

    public function __construct(private Security $security)
    {}

    public function prePersist(Conversation $conversation, LifecycleEventArgs $event) :void
    {
        if($this->security->getUser()){
            $conversation->setUser($this->security->getUser());
        }
    }

}