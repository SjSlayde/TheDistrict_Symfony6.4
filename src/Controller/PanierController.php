<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Plat;
use App\Repository\PlatRepository;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session,PlatRepository $PlatRepo): Response
    {

        $panier = $session->get('panier', []);

        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $plat = $PlatRepo->find($id);
            $dataPanier[] = [
                "plat" => $plat ,
                "quantite" => $quantite
            ];

            $total += $plat->getPrix() * $quantite;
        }

        return $this->render('panier/index.html.twig', compact("dataPanier", 
        "total"));
    }

    #[Route('/panier/ajout/{id}', name: 'app_addpanier', requirements: ['id' => '\d+'])]
    public function AddDish(SessionInterface $session,Plat $plat): Response
    {
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        // dd($session->get('panier'));

        return $this->redirectToRoute('app_panier');
    }
}
