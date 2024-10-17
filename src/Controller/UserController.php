<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{

    private array $PswError = ["title" => "Erreur","description" => "Veuillez mettre le même mot de passe deux fois."];

    #[Route('/user_connect', name: 'app_user_connect')]
    public function login(Request $session): Response
    {
        return $this->render('user/connect.html.twig');
    }

    #[Route('/user_create', name: 'app_user_create_form')]
    public function create(Request $session): Response
    {
        return $this->render('user/create.html.twig',['error' => null ]);
    }

    #[Route('/create', name: 'app_user_create')]
    public function create_user(Request $request): Response
    {   

        $email = $request->request->get('email');
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirm_password');
        if ($password !== $confirmPassword) {
            return $this->render('user/create.html.twig', ['error' => $this->PswError]);
        }

        return $this->redirectToRoute('app_mainController');
    }

}
