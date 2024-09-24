<?php 

    namespace App\Service;
    use App\Repository\CategorieRepository;
    use App\Repository\PlatRepository;

class SearchService{

    private $platRepo;
    private $catRepo;

    public function __construct(PlatRepository $platRepo,CategorieRepository $catRepo){

        $this->platRepo = $platRepo;
        $this->catRepo = $catRepo;

    }

    public function search($recherche):array{
        if($this->SearchPlat($recherche) != null){
            $result = $this->SearchPlat($recherche);
        } elseif($this->SearchCategorie($recherche) != null){
            $result = $this->SearchCategorie($recherche);
        } else {
            $result = [];
        }

        return $result;
    }

    public function SearchPlat($recherche):array{

        $plats = $this->platRepo->findAll();
        $platrecherche = [];
    
    foreach($plats as $plat){
        if (str_contains(strtolower($plat->getLibelle()), $recherche)) {
        
        array_push($platrecherche, $plat);

        } 
    }
    return $platrecherche;
    }

    public function SearchCategorie($recherche):array{

        $categories = $this->catRepo->findAll();
        $catrecherche = [];
    
    foreach($categories as $categorie){
        if (str_contains(strtolower($categorie->getLibelle()), $recherche)) {
        
        array_push($catrecherche, $categorie);

        } 
    }

    return $catrecherche;
    }

}


