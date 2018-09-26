<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Student;
use App\Entity\Document;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;  

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(Request $request)
    {
    $document = new Document();
    $form = $this->createFormBuilder($document)
        ->add('name', TextType::class) 
        ->add('file', FileType::class, array('label' => 'Photo (png, jpeg)')) 
        ->getForm();
    $form->handleRequest($request);
   
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $destination = $this->getParameter('photos_directory');
            $document->upload($destination);
            $em->persist($document);
            $em->flush();
            return $this->redirectToRoute('upload');
        }
        return $this->render('upload/index.html.twig',[
        'form' => $form->createView(),
        'controller_name' => 'Upload files',
        ]);
    }
}
    
 
