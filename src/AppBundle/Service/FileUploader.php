<?php
// src/AppBundle/Service/FileUploader.php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * https://symfony.com/doc/3.4/controller/upload_file.html
 * Class FileUplodaer
 */
class FileUploader
{
    private $targetDirectory;

    /**
     * FileUploader constructor
     *
     * @param string $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file UploadedFile
     *
     * @return string File name
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * Get Target Directory
     *
     * @return string Target directory
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
