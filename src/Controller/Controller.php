<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    #[Route('/', name: 'app_mainController')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('connecte',$session->get('connecte'));

        $items = [
            "1" => ["title" => "Chèvre Miel",
                    "content" => "du chèvre et du miel",
                    "img" => "coucou",
                    "trucs" => "trop bon whalah"
        ],
                "2" => ["title" => "Chèvre Miel",
                    "content" => "du chèvre et du miel",
                    "img" => "coucou",
                    "trucs" => "trop bon whalah"
    ],
                    "3" => ["title" => "Chèvre Miel",
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

    #[Route('/PHPInformations', name: 'PHPInformations')]
    public function testPHPInformations(Request $request): Response
    {
        return $this->render('tests/test.php');
    }
}
