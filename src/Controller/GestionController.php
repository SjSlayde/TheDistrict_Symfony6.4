<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GestionController extends AbstractController
{

    private $platRepo;
    private $categoryRepo;

    public function __construct(CategorieRepository $categoryRepo, PlatRepository $platRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->platRepo = $platRepo;

    }

    #[Route('/gestion', name: 'app_gestion')]
    public function gestion(): Response
    {

        $category = $this->categoryRepo->findAll();
        $plat = $this->platRepo->findAll();
    
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'CatalogueController',
            'category' => $category,
            'plat' => $plat
        ]);
    }

    #[Route('/gestion/plat', name: 'app_gestion_plat')]
    public function gestionPlat(): Response
    {
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }

    #[Route('/gestion/categorie', name: 'app_gestion_categorie')]
    public function gestionCategorie(): Response
    {
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
}
