<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use function PHPUnit\Framework\isNull;

class FileUploader
{
    public function __construct(
        private $targetDirectory,
        private SluggerInterface $slugger
    ){}

    /**
     * @throws Exception
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $exception) {
            throw new Exception($exception->getMessage());
        }

        return $fileName;
    }

    /**
     * @throws Exception
     */
    public function editContactUpload($contactPicture, $contact): string
    {
        try {
            if(!empty($contactPicture) && !isNull($contactPicture)) {
                return $contact->setPicture(
                    new File($this->getParameter('contact_directory') . '/' . $contact->getPicture())
                );
            }
        }catch (FileException $exception){
            throw new Exception($exception->getMessage());
        }
        return $contactPicture;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}