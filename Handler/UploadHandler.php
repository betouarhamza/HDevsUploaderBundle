<?php
/**
 * Created by Hamza Betouar
 * Email: betouar.hamza.89@gmail.com
 */
namespace HDevs\UploaderBundle\Handler;

use HDevs\UploaderBundle\Annotation\UploadableProperty;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadHandler {

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    /**
     * UploadHandler constructor.
     */
    public function __construct() {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $entity
     * @param $property
     * @param UploadableProperty $annotation
     */
    public function uploadFile($entity, $property, UploadableProperty $annotation) {
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof UploadedFile) {
            $this->removeOldFile($entity, $annotation);
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($annotation->getPath(), $filename);
            $this->accessor->setValue($entity, $annotation->getField(), $filename);
        }
    }

    /**
     * @param $entity
     * @param $property
     * @param UploadableProperty $annotation
     */
    public function setFileFromField($entity, $property, UploadableProperty $annotation)
    {
        $file = $this->getFileFromField($entity, $annotation);
        $this->accessor->setValue($entity, $property, $file);
    }

    /**
     * @param $entity
     * @param UploadableProperty $annotation
     */
    public function removeOldFile($entity, UploadableProperty $annotation)
    {
        $file = $this->getFileFromField($entity, $annotation);
        if ($file !== null) {
            @unlink($file->getRealPath());
        }
    }

    /**
     * @param $entity
     * @param $property
     */
    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);
        if ($file instanceof File) {
            @unlink($file->getRealPath());
        }
    }

    /**
     * @param $entity
     * @param UploadableProperty $annotation
     * @return null|File
     */
    private function getFileFromField ($entity, UploadableProperty $annotation) {
        $filename = $this->accessor->getValue($entity, $annotation->getField());
        if (empty($filename)) {
            return null;
        } else {
            return new File($annotation->getPath() . DIRECTORY_SEPARATOR . $filename, false);
        }
    }

}