<?php

namespace App\EventListener;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Report::class)]
class ReportEntityListener {

    public function __construct(private Security $security)
    {}

    public function prePersist(Report $report, LifecycleEventArgs $event) :void
    {
        if($this->security->getUser()){
            $report->setUser($this->security->getUser());
        }
    }

}