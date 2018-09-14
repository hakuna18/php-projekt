<?php
/**
 * CoverUploadListener.
 */
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Product;
use AppBundle\Service\FileUploader;
use AppBundle\Entity\Book;

/** https://symfony.com/doc/3.4/controller/upload_file.html
 *
 * Class CoverUploadListener
 *
 * */
class CoverUploadListener
{
    /**
     * File uploader service
     */
    private $uploader;

    /**
     * CoverUploadListener constructor.
     *
     * @param AppBundle\Service\FileUploader $uploader File uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * PrePersist
     *
     * @param Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * PreUpdate
     *
     * @param Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * UploadFile
     *
     * @param AppBundle\Entity\Book $entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Book) {
            return;
        }

        $file = $entity->getCover();
        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setCover($fileName);
        }
    }
}
