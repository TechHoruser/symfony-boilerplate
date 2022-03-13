<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AuditableEntitySubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (method_exists($object, 'setCreatedAt')) {
            $object->setCreatedAt(new \DateTime());
        }

        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if (method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new \DateTime());
        }
    }

}
