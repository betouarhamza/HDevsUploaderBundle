<?php
/**
 * Created by Hamza Betouar
 * Email: betouar.hamza.89@gmail.com
 */

namespace HDevs\UploaderBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;


/**
 * @Annotation
 * @Target("PROPERTY")
 */
class UploadableProperty
{

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $path;

    /**
     * UploadableProperty constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if( !isset($options['field']) )
            throw new \InvalidArgumentException("option 'field' is not defined");

        if( !isset($options['path']) )
            throw new \InvalidArgumentException("option 'path' is not defined");

        $this->field = $options['field'];
        $this->path = $options['path'];
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}