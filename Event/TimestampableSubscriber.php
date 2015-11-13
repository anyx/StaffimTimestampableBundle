<?php

namespace Staffim\TimestampableBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Staffim\TimestampableBundle\Model\TimestampableInterface;

class TimestampableSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    /**
     * @return \DateTime
     */
    public function now()
    {
        return now();
    }

    /**
     * @param \Doctrine\Common\Persistence\Event\LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        if (!$this->supports($event->getObject())) {
            return;
        }

        $now = $this->now();

        $event->getObject()->setCreatedAt($now);
        $event->getObject()->setUpdatedAt($now);
    }

    /**
     * @param \Doctrine\Common\Persistence\Event\LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        if (!$this->supports($event->getObject())) {
            return;
        }

        $object = $event->getObject();
        $object->setUpdatedAt($this->now());
        $dm = $event->getDocumentManager();
        $class = $dm->getClassMetadata(get_class($object));
        $dm->getUnitOfWork()->recomputeSingleDocumentChangeSet($class, $object);
    }

    /**
     * @param object $object
     * @return bool
     */
    public function supports($object)
    {
        return $object instanceof TimestampableInterface;
    }
}
