<?php

namespace App\Controller;

use App\Entity\Blogs;
use App\Entity\Utilisateur;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{
    #[Route('/create_blog', name: 'app_blog_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $blog = new Blogs();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('Image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('blog_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e->getMessage());
                }

                $blog->setFileName($newFilename);
            }

            // Handle the session
            $session = $request->getSession();
            $repository = $doctrine->getRepository(Utilisateur::class);

            // Get and decode the ingredients JSON list
            $ingredientsJson = $form->get('ingredientList')->getData();
            $ingredients = json_decode($ingredientsJson, true);

            if (is_array($ingredients)) {
                // ajoute les ingrédients à la liste des ingrédients du blogs
                $blog->setIngredients($ingredients);
            }else{
                $blog->setIngredients([]);
            }

            // Set the user associated with the blog
            $user = $repository->findOneBy(["email" => $session->get('email')]);
            $blog->setUtilisateur($user);

            // Persist the blog entity
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_mainController');
        }

        return $this->render('blog/create_blog.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
