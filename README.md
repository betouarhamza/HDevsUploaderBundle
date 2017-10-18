Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require hdevs/uploader-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new HDevs\UploaderBundle\HDevsUploaderBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Example
-------------------------

```php
<?php
//Entity

use HDevs\UploaderBundle\Annotation\Uploadable;
use HDevs\UploaderBundle\Annotation\UploadableProperty;
/**
 * @ORM\Table(name="post")
 * @Uploadable()
 */
class Post
{
    // Other fields
    
    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var File
     * @UploadableProperty(field="image", path="uploads/posts")
     */
    private $file;
}
``` 

```php
<?php
//FormType

use Symfony\Component\Form\Extension\Core\Type\FileType;
$builder->add('file', FileType::class, [
                'label' => 'global.image'
            ]);
``` 

```html
<!-- View -->
<img src="{{ asset(post.file.pathname) }}">
``` 