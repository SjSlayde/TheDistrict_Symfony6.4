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
        $this->requestStack = $requestStack; // Gère la requête HTTP, y compris la session
        $this->PlatRepo= $PlatRepo; // Repository pour accéder aux plats
    }
   
        /**
     * Récupère le panier de la session sous forme de tableau associatif
     * contenant les identifiants des plats et les quantités
     *
     * @return array
     */
    public function ShowPanier(): array
    {
        $session = $this->requestStack->getSession();
    
         // Retourne le panier depuis la session, ou un tableau vide si aucun panier n'existe
        return $session->get('panier', []);
    }

     /**
     * Récupère les données complètes du panier, en associant chaque plat avec sa quantité
     *
     * @return array
     */

      // Parcourt chaque élément du panier et récupère les détails du plat depuis la base de données
    public function ShowDataPanier(): array{
        $panier = $this->ShowPanier(); // Récupère le panier actuel

        $dataPanier = [];

        foreach($panier as $id => $quantite){
            $plat = $this->PlatRepo->find($id);  // Cherche le plat par son ID
            $dataPanier[] = [
                "plat" => $plat ,   // Ajoute l'objet Plat dans le tableau
                "quantite" => $quantite  // Ajoute la quantité associée au plat
            ];

        }
        return $dataPanier;  // Retourne le panier avec les objets Plat et quantités
    }

    /**
     * Calcule le total du panier en fonction des prix des plats et des quantités
     *
     * @return int
     */
    public function getTotal(): int {

            $panier = $this->ShowPanier();  // Récupère le panier actuel
            $total = 0;
    
            // Parcourt chaque élément du panier et calcule le total en multipliant prix et quantité
            foreach($panier as $id => $quantite){
                $plat = $this->PlatRepo->find($id); // Cherche le plat par son ID
                $total += $plat->getPrix() * $quantite; // Additionne le total
            }

            return $total;  // Retourne le total du panier
    }
    
        /**
     * Ajoute un plat dans le panier ou incrémente la quantité s'il existe déjà
     *
     * @param Plat $plat
     */
    public function AddOneDish(Plat $plat): Void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []);// Récupère le panier ou un tableau vide
        $id = $plat->getId();// Récupère l'ID du plat
    
        // Si le plat est déjà dans le panier, on incrémente la quantité, sinon on l'ajoute
        if (!empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }
    
        // Sauvegarde le panier mis à jour dans la session
        $session->set('panier', $panier);
    
    }
   

    /**
     * Réduit la quantité d'un plat dans le panier ou le supprime si la quantité atteint 0
     *
     * @param Plat $plat
     */
    public function RemoveOneQuantity(Plat $plat): void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []); // Récupère le panier
        $id = $plat->getId(); // Récupère l'ID du plat

        // Si le plat est dans le panier et que la quantité est supérieure à 1, on la réduit
        if (!empty($panier[$id])){
            if ($panier[$id] > 1){
            $panier[$id]--;
        } else {
            unset($panier[$id]); // Si la quantité est 1, on supprime le plat du panier
        }}

        // Sauvegarde le panier mis à jour dans la session
        $session->set('panier', $panier);

    }

        /**
     * Supprime complètement un plat du panier
     *
     * @param Plat $plat
     */
    public function DeleteOneDish(Plat $plat): void
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier', []); // Récupère le panier
        $id = $plat->getId();// Récupère l'ID du plat

        // Si le plat est dans le panier, on le supprime
        if (!empty($panier[$id])){
            unset($panier[$id]);}

        // Sauvegarde le panier mis à jour dans la session
        $session->set('panier', $panier);

    }

    /**
     * Vide complètement le panier
     */
    public function DeleteAllDish(): void
    {
        $session = $this->requestStack->getSession();
        // Supprime tous les plats du panier en le réinitialisant à un tableau vide
        $session->set('panier', []);
        // $session->remove('panier'); Les deux commandes sont identiques. PS : ceux qui ont cette même ligne en commentaire, je vous vois ;)
    }
}