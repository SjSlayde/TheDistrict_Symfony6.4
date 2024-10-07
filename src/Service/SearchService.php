<?php 

namespace App\Service;

use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;

class SearchService
{
    // Déclaration des propriétés pour stocker les repositories
    private $platRepo;
    private $catRepo;

    // Constructeur pour injecter les repositories Plat et Categorie
    public function __construct(PlatRepository $platRepo, CategorieRepository $catRepo)
    {
        // Initialisation des repositories
        $this->platRepo = $platRepo;
        $this->catRepo = $catRepo;
    }

    /**
     * Méthode principale de recherche qui tente de rechercher d'abord par plat,
     * puis par catégorie, et retourne le résultat
     * 
     * @param string $recherche - Terme de recherche
     * @return array - Résultat de la recherche
     */
    public function search($recherche): array
    {
        // Recherche dans les plats d'abord
        if ($this->SearchPlat($recherche) != null) {
            $result = $this->SearchPlat($recherche);  // Si un plat est trouvé, on retourne ce résultat
        } 
        // Si aucun plat n'est trouvé, on recherche dans les catégories
        elseif ($this->SearchCategorie($recherche) != null) {
            $result = $this->SearchCategorie($recherche);  // Si une catégorie est trouvée, on retourne ce résultat
        } 
        // Si rien n'est trouvé dans les plats ni les catégories, on retourne un tableau vide
        else {
            $result = [];
        }

        return $result;
    }

    /**
     * Recherche parmi les plats si le terme de recherche correspond à leur libellé
     * 
     * @param string $recherche - Terme de recherche
     * @return array - Liste des plats correspondants
     */
    public function SearchPlat($recherche): array
    {
        // Récupère tous les plats
        $plats = $this->platRepo->findAll();
        $platrecherche = [];  // Tableau pour stocker les résultats

        // Parcourt chaque plat pour vérifier s'il contient le terme de recherche
        foreach ($plats as $plat) {
            if (str_contains(strtolower($plat->getLibelle()), $recherche)) {
                // Ajoute le plat dans le tableau des résultats si le terme de recherche est trouvé
                array_push($platrecherche, $plat);
            }
        }
        return $platrecherche;  // Retourne les plats correspondants
    }

    /**
     * Recherche parmi les catégories si le terme de recherche correspond à leur libellé
     * 
     * @param string $recherche - Terme de recherche
     * @return array - Liste des catégories correspondantes
     */
    public function SearchCategorie($recherche): array
    {
        // Récupère toutes les catégories
        $categories = $this->catRepo->findAll();
        $catrecherche = [];  // Tableau pour stocker les résultats

        // Parcourt chaque catégorie pour vérifier si elle contient le terme de recherche
        foreach ($categories as $categorie) {
            if (str_contains(strtolower($categorie->getLibelle()), $recherche)) {
                // Ajoute la catégorie dans le tableau des résultats si le terme de recherche est trouvé
                array_push($catrecherche, $categorie);
            }
        }

        return $catrecherche;  // Retourne les catégories correspondantes
    }
}
// petit compliment de chatgpt quand je lui demandais de commenter: 
// "Le code est clair et bien structuré. Chaque méthode est responsable d'une recherche précise (dans les plats ou dans les catégories). 
//  Cela garantit une recherche efficace dans la base de données."