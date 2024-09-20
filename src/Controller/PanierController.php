<?php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Plat;

class PanierController extends AbstractController
{
    private $PS;

    public function __construct(PanierService $PanierService){
        $this->PS = $PanierService;
    }
    
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        $panier = $this->PS->ShowPanier();

        $dataPanier = $this->PS->ShowDataPanier();
        $total = $this->PS->getTotal();

        count($dataPanier);

        return $this->render('panier/index.html.twig', compact("dataPanier", 
        "total"));
    }

    #[Route('/panier/ajout/{id}', name: 'app_ajout_panier', requirements: ['id' => '\d+'])]
    public function AjoutDish(Plat $plat): Response
    {
        $this->PS->AddOneDish($plat);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/enlever/{id}', name: 'app_enlever_panier', requirements: ['id' => '\d+'])]
    public function RemoveOneQuantity(Plat $plat): Response
    {
         $this->PS->RemoveOneQuantity($plat);

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/supprimer/{id}', name: 'app_supprimer_panier', requirements: ['id' => '\d+'])]
    public function DeleteOneDish(Plat $plat): Response
    {
        $this->PS->DeleteOneDish($plat);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/all', name: 'app_supprimer_panier_all')]
    public function DeleteAllDish(): Response
    {
        // $session->remove('panier');
        $this->PS->DeleteAllDish();
        $this->addFlash('success','Votre panier a été vidées');
        return $this->redirectToRoute('app_panier');
    }
}
