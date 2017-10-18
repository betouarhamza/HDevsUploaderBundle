<?php
/**
 * Created by Hamza Betouar
 * Email: betouar.hamza.89@gmail.com
 */
namespace HDevs\UploaderBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use HDevs\UploaderBundle\Annotation\UploaderAnnotationReader;
use HDevs\UploaderBundle\Handler\UploadHandler;

class UploadSubscriber implements EventSubscriber {

    /**
     * @var UploaderAnnotationReader
     */
    private $reader;

    /**
     * @var UploadHandler
     */
    private $handler;

    public function __construct(UploaderAnnotationReader $reader, UploadHandler $handler)
    {
        $this->reader = $reader;
        $this->handler = $handler;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove'
        ];
    }

    public function prePersist(LifecycleEventArgs $event) {
        $this->preEvent($event);
    }

    public function preUpdate(LifecycleEventArgs $event) {
        $this->preEvent($event);
    }

    private function preEvent(LifecycleEventArgs $event) {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableProperties($entity) as $property => $annotation) {
            $this->handler->uploadFile($entity, $property, $annotation);
        }
    }

    public function postLoad(LifecycleEventArgs $event) {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableProperties($entity) as $property => $annotation) {
            $this->handler->setFileFromField($entity, $property, $annotation);
        }
    }

    public function postRemove(LifecycleEventArgs $event) {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableProperties($entity) as $property => $annotation) {
            $this->handler->removeFile($entity, $property);
        }
    }
}