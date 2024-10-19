<?php

namespace App\Controller;

use App\Entity\Blogs;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;
use Doctrine\ORM\QueryBuilder;

class Controller extends AbstractController
{
    #[Route('/', name: 'app_mainController')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $session = $request->getSession();
        $session->set('connecte',$session->get('connecte'));

        //ajout des derniers forums a la liste
        $items = $doctrine->getRepository(persistentObject: Blogs::class)->findBy([],['id' => 'desc']);

        $ingredients = $this->getFiltres($items);

        return $this->render('base.html.twig', ['items' => $items, "ingredients" => $ingredients]);
    }

    #[Route('/search/user/{user}', name: 'user_blogs')]
    public function search_user(Request $request, $user, ManagerRegistry $doctrine): Response
    {

        $criteria = ['utilisateur' => $doctrine->getRepository(persistentObject: Utilisateur::class)->findOneBy(['surnom' => $user])];

        $items = $this->filtre($request, $doctrine, $criteria);

        $ingredients = $this->getFiltres($items);

        return $this->render('base.html.twig', ['items' => $items, "ingredients" => $ingredients,"path_to_public" => '../../']);
    }

    #[Route('/filtres', name: 'blogs_filtre')]
    public function filtre_get(Request $request, ManagerRegistry $doctrine): Response
    {
        //on génère les 
        

        $items = $this->filtre($request, $doctrine);

        $ingredients = $this->getFiltres($items);

        return $this->render('base.html.twig', ['items' => $items, "ingredients" => $ingredients,"path_to_public" => '../../']);
    }
    
    
    public function filtre(Request $request, ManagerRegistry $doctrine, array $criteria = []): array
    {
        $selectedIngredients = $request->get('selected_ingredients', []);

        
        /** @var EntityRepository $entityManager */
        $entityManager = $doctrine->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        // On prepare la 
        $queryBuilder->select('b')
            ->from(Blogs::class, 'b');

        // Add additional criteria to the query
        foreach ($criteria as $field => $value) {
            // Ensure that the field is valid and prevent SQL injection
            if (property_exists(Blogs::class, $field)) {
                $queryBuilder->andWhere("b.$field = :$field")
                    ->setParameter($field, $value);
            }
        }

        // Add conditions for selected ingredients
        if (!empty($selectedIngredients)) {
            $orX = $queryBuilder->expr()->orX();
            foreach ($selectedIngredients as $ingredient) {
                $orX->add(
                    $queryBuilder->expr()->like('b.Ingredients', $queryBuilder->expr()->literal('%"' . $ingredient . '"%'))
                );
            }

            // Add the OR condition to the query
            $queryBuilder->andWhere($orX);
        }

        $queryBuilder->orderBy('b.id', 'DESC');

        // Get the results
        return $queryBuilder->getQuery()->getResult();
    }



    /**
     * @param Blogs[] $items
     * @return array
     */
    private function getFiltres(array $items): array{

        $ingredients = [];
        foreach($items as $item){
            foreach ($item->getIngredients() as $ingredient) {
                if(isset($ingredients[$ingredient])){
                    $ingredients[$ingredient]++;
                }else{
                    $ingredients[$ingredient]=1;
                }
            }
        }
        arsort($ingredients);
        return $ingredients;
    }


    #[Route('/search/blog', name: 'search_blog')]
    public function searchBlog(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $blogsRepository = $entityManager->getRepository(Blogs::class);

        $search = $request->get('search');
    
        $query = $blogsRepository->createQueryBuilder('b')
            ->where('b.title LIKE :search')
            ->orWhere('b.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('b.id', 'DESC')
            ->getQuery();
    
        $items = $query->getResult();
    
        $ingredients = $this->getFiltres($items);
    
        return $this->render('base.html.twig', [
            'items' => $items,
            'ingredients' => $ingredients,
            'path_to_public' => '../../',
            'search_term' => $search
        ]);
    }

}
