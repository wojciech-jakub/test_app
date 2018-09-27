<?php

namespace App\Controller;

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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(Request $request)
    {
        $session = $this->get('session');
        $document = new Document();

        if($session->has('login')){
            $file = $request->files->get('upload');
            if($file){

                $name = $request->request->get('firstName');
                $secondName = $request->request->get('secondName');
                $destination = $this->getParameter('photos_directory');
                $filename = uniqid().".".$file->getClientOriginalExtension();
                $path = $destination;
                $file->move($path,$filename);
                $em = $this->getDoctrine()->getManager();
                $document->setName($name);
                $document->setSecondName($secondName);
                $document->setPath($filename);
                $em->persist($document);
                $em->flush();

                return new JsonResponse($file);
            }
            if($request->request->get('logout')){
                $session->invalidate();
                return $this->redirect($this->generateUrl('login'));
            }
        return $this->render('upload/index.html.twig',[
            'controller_name' => 'Upload files',
        ]);
    }
    else{
        return $this->redirect($this->generateUrl('login'));
    }
}

    /**
    * @Route("/files", name="file")
    */
     public function files()
    {
        $session = $this->get('session');
        if($session->has('login')){
            $documents = $this->getDoctrine()
                ->getRepository(Document::class)
                ->findAll();

            return $this->render('upload/file.html.twig',[
                'documents' => $documents,
            ]);
        }
        else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
    /**
    * @Route("/download/{id}",name="image_download")
    */
    public function downloadAction($id) {
        $session = $this->get('session');
        if($session->has('login')){
            $document = $this->getDoctrine()
                ->getRepository(Document::class)
                ->findOneBy(['id' => $id]);
            $downloadedFile = $document;
            $response=new Response();

            $destination = $this->getParameter('photos_directory');
            $docpath = $document->getPath();
            $absolutePath = "$destination"."/$docpath";

            $response = new Response();
            $response->headers->set('Content-type', 'application/jpeg');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"',$downloadedFile->getPath() ));
            $response->setContent(file_get_contents($absolutePath));
            $response->setStatusCode(200);
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;
        }
        else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
    /**
    * @Route("/show/{id}",name="show")
    */
    public function showAction($id) {
        $session = $this->get('session');
        if($session->has('login')){
            $document = $this->getDoctrine()
                ->getRepository(Document::class)
                ->findOneBy(['id' => $id]);
            $destination = $this->getParameter('photos_directory');
            $docpath = $document->getPath();
            $absolutePath = "$destination"."/$docpath";

            return new BinaryFileResponse($absolutePath);
        }
        else{
            return $this->redirect($this->generateUrl('login'));
        }
    }
}

