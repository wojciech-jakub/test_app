<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request)
    {
        $session = new Session();

        if($request->request->get('log')){
            $login = $request->request->get('login');
            $password = $request->request->get('password');
            $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['password' => $password,
                'login' => $login]);

            if(!($user === NULL)){
                if($session->get('session')){
                    $sesion->get('login');
                }
                else{
                    $session->start();
                    $session->set('login',$login);
                }
                return $this->redirect($this->generateUrl('upload'));
            }
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'Login',
        ]);
    }
}
