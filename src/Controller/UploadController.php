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
use Symfony\Component\HttpFoundation\JsonResponse;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(Request $request)
    {
        $document = new Document();
   
        $file = $request->files->get('upload');
     
        if($file){
               
            $name = $request->request->get('username');
            $destination = $this->getParameter('photos_directory');
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $path = $destination;
            $file->move($path,$filename);
            $em = $this->getDoctrine()->getManager();
            $document->setName($name);
            $document->setPath("/web/uploads/photo/".$filename);
            $em->persist($document);
            $em->flush();

            return new JsonResponse($file);
        }
        return $this->render('upload/index.html.twig',[
            'controller_name' => 'Upload files',
        ]);
    }
}
    
 
