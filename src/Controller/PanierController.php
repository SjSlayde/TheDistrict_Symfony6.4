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

        count($dataPanier);

        return $this->render('panier/index.html.twig', compact("dataPanier", 
        "total"));
    }

    #[Route('/panier/ajout/{id}', name: 'app_ajout_panier', requirements: ['id' => '\d+'])]
    public function AjoutDish(SessionInterface $session,Plat $plat): Response
    {
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/enlever/{id}', name: 'app_enlever_panier', requirements: ['id' => '\d+'])]
    public function EnleverDish(SessionInterface $session,Plat $plat): Response
    {
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            if ($panier[$id] > 1){
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }}

        $session->set('panier', $panier);


        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/supprimer/{id}', name: 'app_supprimer_panier', requirements: ['id' => '\d+'])]
    public function DeleteDish(SessionInterface $session,Plat $plat): Response
    {
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            unset($panier[$id]);}

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/all', name: 'app_supprimer_panier_all')]
    public function DeleteAllDish(SessionInterface $session): Response
    {
        // $session->remove('panier');
        $session->set('panier', []);

        return $this->redirectToRoute('app_panier');
    }
}
