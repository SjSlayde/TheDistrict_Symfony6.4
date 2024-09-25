<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Plat;
use App\Entity\Utilisateur;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService
{
    private $requestStack;
    private $PlatRepo;

    public function __construct(RequestStack $requestStack,PlatRepository $PlatRepo)
    {
        $this->requestStack = $requestStack;
        $this->PlatRepo= $PlatRepo;
    }
   
    public function ShowPanier(): array
    {
        $session = $this->requestStack->getSession();
    
        return $session->get('panier', []);
    }

    public function ShowDataPanier(): array{
        $panier = $this->ShowPanier();

        $dataPanier = [];

        foreach($panier as $id => $quantite){
            $plat = $this->PlatRepo->find($id);
            $dataPanier[] = [
                "plat" => $plat ,
                "quantite" => $quantite
            ];

        }
        return $dataPanier;
    }

    public function getTotal(): int {

            $panier = $this->ShowPanier();
            $total = 0;
    
            foreach($panier as $id => $quantite){
                $plat = $this->PlatRepo->find($id);
                $total += $plat->getPrix() * $quantite;
            }

            return $total;
    }
    
    public function AddOneDish(Plat $plat): Void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []);
        $id = $plat->getId();
    
        if (!empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }
    
        $session->set('panier', $panier);
    
    }
   
    public function RemoveOneQuantity(Plat $plat): void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            if ($panier[$id] > 1){
            $panier[$id]--;
        } else {
            unset($panier[$id]);
        }}

        $session->set('panier', $panier);

    }

    public function DeleteOneDish(Plat $plat): void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if (!empty($panier[$id])){
            unset($panier[$id]);}

        $session->set('panier', $panier);

    }

    public function DeleteAllDish(): void
    {
        $session = $this->requestStack->getSession();
        // $session->remove('panier');
        $session->set('panier', []);
    }
}