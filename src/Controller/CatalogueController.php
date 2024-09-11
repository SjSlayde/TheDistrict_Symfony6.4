<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;

class CatalogueController extends AbstractController
{

    private $categoryRepo;
    private $platRepo;

    public function __construct(CategorieRepository $categoryRepo, PlatRepository $platRepo)
    {
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


    #[Route('/plats/{categorie_id}', name: 'app_dishincat' , requirements: ['categorie_id' => '\d+'])]
    public function ShowDishForCat(int $categorie_id): Response
    {
        $categorie = $this->categoryRepo->find($categorie_id);

        $plat = $this->platRepo->findBy(['categorie' => $categorie->getId()]);
        return $this->render('catalogue/dishincat.html.twig', [
            'controller_name' => 'CatalogueController',
            'plat'=> $plat,
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
}
