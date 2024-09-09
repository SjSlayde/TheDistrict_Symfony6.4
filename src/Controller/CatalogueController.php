<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    #[Route('/plats', name: 'app_dish')]
    public function ShowDish(): Response
    {
        return $this->render('catalogue/dish.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }


    #[Route('/plats/{categorie_id}', name: 'app_dishincat')]
    public function ShowDishForCat(): Response
    {
        return $this->render('catalogue/dishincat.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    #[Route('/categories', name: 'app_category')]
    public function ShowCat(): Response
    {
        return $this->render('catalogue/category.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }
}
