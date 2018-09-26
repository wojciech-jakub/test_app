<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;



/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class Files
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $name;

     /**
     * @Assert\File(maxSize="6000000")
     * @Assert\NotBlank(message="Please, upload the photo.") 
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" }) 
     */ 
     private $file;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
   
     /**
      * Sets file.
      *
      * @param UploadedFile $file
      */
     public function setFile(UploadedFile $file = null)
     {
         $this->file = $file;
     }
 
     /**
      * Get file.
      *
      * @return UploadedFile
      */
     public function getFile()
     {
         return $this->file;
     }
}
