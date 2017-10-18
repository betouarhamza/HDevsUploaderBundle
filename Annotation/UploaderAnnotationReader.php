<?php
/**
 * Created by Hamza Betouar
 * Email: betouar.hamza.89@gmail.com
 */

namespace HDevs\UploaderBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;

class UploaderAnnotationReader
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * UploaderAnnotationReader constructor.
     * @param AnnotationReader $reader
     */
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \ReflectionClass $reflection
     * @return bool
     */
    public function isUploadable(\ReflectionClass $reflection): bool{
        return $this->reader->getClassAnnotation($reflection, Uploadable::class) !== null;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getUploadableProperties($entity){
        $reflection = $this->getReflection($entity);
        if(!$this->isUploadable($reflection)) return [];

        $properties = [];
        foreach ($reflection->getProperties() as $property){
            $annotation = $this->reader->getPropertyAnnotation($property, UploadableProperty::class);
            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;
            }
        }
        return $properties;
    }

    /**
     * @param $entity
     * @return \ReflectionClass
     */
    private function getReflection($entity): \ReflectionClass{
        return new \ReflectionClass(get_class($entity));
    }
}