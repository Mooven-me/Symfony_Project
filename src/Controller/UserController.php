<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController{

    //message d'erreur de la connfirmation de mot de passe mauvaise
    private array $PswError = ["title" => "Erreur","description" => "Veuillez mettre le même mot de passe deux fois."];
    //message d'erreur de l'email déjà utilisé
    private array $EmailError = ["title" => "Erreur","description" => "Email déjà utilisé / compte déjà existant"];
    private array $LoginError = ["title" => "Erreur","description" => "Mot de passe / Email incorrect"];


    //route pour afficher le formulaire de connexion
    #[Route('/user_connect', name: 'app_user_connect')]
    public function login(Request $session): Response
    {
        return $this->render('user/connect.html.twig');
    }

    //route pour afficher le formulaire d'inscription
    #[Route('/user_create', name: 'app_user_create_form')]
    public function create(Request $session): Response
    {
        return $this->render('user/create.html.twig');
    }

    //route pour se connecter à un user
    #[Route('/user_connect_valid', name: 'app_user_cconnect_valid')]
    public function connectValid(Request $request, ManagerRegistry $doctrine): Response
    {

        //récupération des paramètres du formulaire de connexion
        $email = $request->get('email');
        $password = $request->get('password');

        //création du lien vers la base de donnée
        $repository = $doctrine->getRepository(Utilisateur::class);

        //on regarde si l'Utilisateur existe
        $Utilisateur = $repository->findOneBy(['email' => $email, 'mdp' => $password]);

        if($Utilisateur===null){
            return $this->render('user/connect.html.twig',['error' => $this->LoginError]);
        }

        //ajout de la variable de session
        $session = $request->getSession();
        $session->set('connecte', True);
        $session->set('mdp', $password);
        $session->set('email', $email);
        $session->set('surnom', $Utilisateur->getSurnom());


        return $this->redirectToRoute('app_mainController');
    }

    //route pour se déconnecter du compte
    #[Route('/disconnect', name: 'app_user_diconnect')]
    public function disconnect(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        $session->set('connecte', false);

        return $this->redirectToRoute('app_mainController');
    }

    //route pour créer le compte utilisateur
    #[Route('/create', name: 'app_user_create')]
    public function create_user(Request $request, ManagerRegistry $doctrine): Response
    {
        //récupérations des paramètres du form
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $confirmPassword = $request->get('confirm_password');

        //on regarde si els deux mots de passes sont les mêmes
        if ($password !== $confirmPassword) {
            return $this->render('user/create.html.twig', ['error' => $this->PswError]);
        }

        //on regarde si l'email est déjà inscrit
        $repository = $doctrine->getRepository(Utilisateur::class);
        if($repository->findOneBy(["email" => $email]) !== null){
            return $this->render('user/create.html.twig', ['error' => $this->EmailError]);
        }

        //on crée une sessions
        $session = $request->getSession();
        $session->set('connecte', True);
        $session->set('mdp', $password);
        $session->set('email', $email);
        $session->set('surnom', $username);

        //on ajoute a la BDD
        $entityManager = $doctrine->getManager();
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($email);
        $utilisateur->setMdp($password);
        $utilisateur->setSurnom($username);

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('app_mainController');
    }


    #[Route('/delete_account', name: 'delete_account')]
    public function delete(Request $request, ManagerRegistry $doctrine): Response
    {
        //on récupère la session courante
        $session = $request->getSession();

        //on récupère la base de donnée utilisateur
        $repository = $doctrine->getRepository(Utilisateur::class);

        //on récupère le liens avec la BDD
        $manager = $doctrine->getManager();

        //on fait une requete pour supprimer le user de la table
        $manager->remove($repository->findOneBy(["email" => $session->get('email')]));
        $manager->flush();

        //on enlève les attibuts de la variable session 
        $session->clear();
        $session->set('connecte', false);

        return $this->redirectToRoute('app_mainController');
    }


}
