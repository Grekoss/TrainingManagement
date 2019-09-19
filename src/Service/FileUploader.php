<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = date('Y').'\\'.$safeFilename.'-'.uniqid().'.'.$file->getExtension();

        try {
            $file->move($this->getTargetDirectory().'\\'.date('Y').'\\', $fileName);
        } catch (FileException $e) {
            $this->logger->error('failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
