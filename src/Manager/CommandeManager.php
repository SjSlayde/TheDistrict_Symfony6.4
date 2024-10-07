<?php 

namespace App\Manager;

use App\Entity\Commande;
use App\Event\CommandeEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommandeManager
{
    private $em;  // Propriété pour l'EntityManager qui interagit avec la base de données
    private $eventDispatcherInterface;  // Propriété pour l'EventDispatcher

    /**
     * Constructeur pour injecter l'EntityManager et l'EventDispatcher
     * 
     * @param EntityManagerInterface $em - Le gestionnaire d'entité Doctrine pour gérer les opérations de persistance
     * @param EventDispatcherInterface $eventDispatcherInterface - Le dispatcher d'événements pour déclencher des événements
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcherInterface)
    {
        // Initialisation des propriétés injectées
        $this->em = $em;
        $this->eventDispatcherInterface = $eventDispatcherInterface;
    }

    /**
     * Méthode pour persister et sauvegarder une commande dans la base de données
     * et dispatcher un événement associé à cette commande.
     * 
     * @param Commande $commande - La commande à persister
     */
    public function setCommande($commande)
    {
        // Vérifie si l'objet passé est bien une instance de la classe Commande
        if ($commande instanceof Commande) {
            // Persiste la commande dans la base de données
            $this->em->persist($commande);
            // Exécute la transaction pour sauvegarder la commande
            $this->em->flush();
            
            // Crée un nouvel événement en passant la commande comme paramètre
            $event = new CommandeEvent($commande);
            // Déclenche l'événement via l'EventDispatcher
            $this->eventDispatcherInterface->dispatch($event);
        }
    }
    // public function setMail($commande){
    //     if($commande instanceof Commande){
    //     $event = new CommandeEvent($commande);
    //     $this->eventDispatcherInterface->dispatch($event);
    //     }
    // }
}