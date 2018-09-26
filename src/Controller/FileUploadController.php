<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Files;
use App\Form\FileTypes;

class FileUploadController extends AbstractController
{
    /**
     * @Route("/file/upload", name="file_upload")
     */
    public function new(Request $request)
    {
        $file_image = new Files();
        $form = $this->createForm(FileTypes::class, $file_image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $file_image->getFile();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('photos_directory'),
                $fileName
            );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $file_image->setFile($fileName);

            // ... persist the $file_image variable or any other work

            return $this->redirect($this->generateUrl('app_product_list'));
        }

        return $this->render('file_upload/index.html.twig', array(
            'form' => $form->createView(),
            'controller_name' => 'FileUploadController',

        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}