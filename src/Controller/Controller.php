<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/', name: 'app_mainController')]
    public function index(): Response
    {
        $items = [
            "1" => ["title" => "Chèvre Miel",
                    "content" => "du chèvre et du miel",
                    "img" => "coucou",
                    "trucs" => "trop bon whalah"
                    ]
        ];

        $ingredients = [
            "banane","chevre","miel","sauce tomate","ananas","kebab"
        ];
        return $this->render('base.html.twig', ['items' => $items, "ingredients" => $ingredients]);
    }
}
