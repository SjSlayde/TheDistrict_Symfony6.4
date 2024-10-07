<?php

namespace App\Controller;

use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;

class CatalogueController extends AbstractController
{

    private $categoryRepo; // Propriété pour le dépôt des catégories
    private $platRepo;  // Propriété pour le dépôt des plats

    /**
     * Constructeur pour injecter les repositories de catégorie et de plat
     * 
     * @param CategorieRepository $categoryRepo - Dépôt pour accéder aux catégories
     * @param PlatRepository $platRepo - Dépôt pour accéder aux plats
     */
    public function __construct(CategorieRepository $categoryRepo, PlatRepository $platRepo)
    {
        // Initialisation des dépôts
        $this->categoryRepo = $categoryRepo;
        $this->platRepo = $platRepo;

    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $category = $this->categoryRepo->findAll();
        $plat = $this->platRepo->findAll();

        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
            'category' => $category,
            'plat' => $plat
        ]);
    }

    #[Route('/plats', name: 'app_dish')]
    public function ShowDish(): Response
    {
        $plat = $this->platRepo->findAll();

        return $this->render('catalogue/dish.html.twig', [
            'controller_name' => 'CatalogueController',
            'plat' => $plat,
        ]);
    }


    #[Route('/plats/{categorie_id}', name: 'app_dishincat', requirements: ['categorie_id' => '\d+'])]
    public function ShowDishForCat(int $categorie_id): Response
    {
        $categorie = $this->categoryRepo->find($categorie_id);

        $plat = $this->platRepo->findBy(['categorie' => $categorie->getId()]);
        return $this->render('catalogue/dishincat.html.twig', [
            'controller_name' => 'CatalogueController',
            'plat' => $plat,
            'categorie' => $categorie
        ]);
    }

    #[Route('/categories', name: 'app_category')]
    public function ShowCat(): Response
    {

        $category = $this->categoryRepo->findAll();

        return $this->render('catalogue/category.html.twig', [
            'controller_name' => 'CatalogueController',
            'category' => $category,
        ]);
    }

    #[Route('/recherche', name: 'app_recherche')]
    public function ShowRecherche(SearchService $searchService): Response
    {
        // Récupère la recherche depuis les paramètres GET
        $recherche = $_GET['recherche'];

        // Si la recherche correspond à un plat, on affiche les plats
        if ($searchService->SearchPlat($recherche) != null) {
            $plat = $searchService->SearchPlat($recherche);
            return $this->render('catalogue/dish.html.twig', [
                'controller_name' => 'CatalogueController',
                'plat' => $plat,
            ]);
        }
        // Si la recherche correspond à une catégorie, on affiche les catégories
        elseif ($searchService->SearchCategorie($recherche) != null) {
            $category = $searchService->SearchCategorie($recherche);
            return $this->render('catalogue/category.html.twig', [
                'controller_name' => 'CatalogueController',
                'category' => $category,
            ]);
        }
        // Si rien n'est trouvé, on redirige vers la page d'accueil
        else {
            return $this->redirectToRoute('app_index');
        }
    }
}