<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\FileType; 

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;  

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request)
    {
        if($request->request->get('login')){
            var_dump($request->request->get('login'));
            exit();
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'Login',
        ]);
    }
    /** 
     * @Route("/student/ajax") 
     */ 
    public function ajaxAction(Request $request) {  
        $students = $this->getDoctrine() 
        ->getRepository(User::class) 
        ->findAll();  
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {  

            $jsonData = ['adgsagasdgsad'];
        return new JsonResponse($jsonData); 
        } else { 
        return $this->render('login/ajax.html.twig'); 
        } 
    }  
    /** 
     * @Route("/student/new") 
    */ 
    public function newAction(Request $request) { 
        $student = new Student(); 
        $form = $this->createFormBuilder($student) 
        ->add('name', TextType::class) 
        ->add('photo', FileType::class, array('label' => 'Photo (png, jpeg)')) 
        ->add('save', SubmitType::class, array('label' => 'Submit')) 
        ->getForm(); 
        
      $form->handleRequest($request); 
    //   var_Dump($student->getPhoto());
    //   VAR_DUMP($request->files->get('pic')->getDescription());
      if($request->request->get('upload')){

        VAR_DUMP($request->files->all());

      }
        if ($form->isSubmitted() && $form->isValid()) { 
            $file = $request->files->get ( 'pic' );
            $file = $student->getPhoto(); 
            $file = $form->get('photo')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
            $file->move($this->getParameter('photos_directory'), $fileName); 
            $student->setPhoto($fileName); 
            return new Response("User photo is successfully uploaded."); 
            } else { 
            return $this->render('login/student.html.twig', array( 
                'form' => $form->createView(), 
            )); 
        } 

    }      

}
