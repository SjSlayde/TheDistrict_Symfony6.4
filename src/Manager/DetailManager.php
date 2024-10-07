<?php 

namespace App\Manager; 

use App\Entity\Detail;
use Doctrine\ORM\EntityManagerInterface;

class DetailManager
{
    private $em;  // Propriété pour l'EntityManager qui interagit avec la base de données

    /**
     * Constructeur pour injecter l'EntityManager
     * 
     * @param EntityManagerInterface $em - Le gestionnaire d'entité Doctrine pour gérer les opérations de persistance
     */
    public function __construct(EntityManagerInterface $em)
    {
        // Initialisation de l'EntityManager
        $this->em = $em;
    }

    /**
     * Méthode pour persister et sauvegarder un détail dans la base de données
     * 
     * @param Detail $Detail - Le détail à persister
     */
    public function setDetail($Detail)
    {
        // Vérifie si l'objet passé est bien une instance de la classe Detail
        if ($Detail instanceof Detail) {
            // Persiste le détail dans la base de données
            $this->em->persist($Detail);
            // Exécute la transaction pour sauvegarder le détail
            $this->em->flush();
        }
    }
}