<?php
namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     * @Assert\NotBlank(message="Please, upload the photo.") 
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" }) 
     */ 
     private $file;

     /**
      * Sets file.
      *
      * @param UploadedFile $file
      */
     public function setFile(UploadedFile $file = null)
     {
         $this->file = $file;
     }
 
     public function getPath(): ?string
    {
        return $this->photo;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
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
      * Get file.
      *
      * @return UploadedFile
      */
     public function getFile()
     {
         return $this->file;
     }
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
    public function upload($destination)
    {
        // the file property can be empty if the field is not required
        if ($this->getFile() === null) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move($destination, $this->getFile()->getClientOriginalName());
        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();
        // clean up the file property as you won't need it anymore
        $this->file = null;
}
}