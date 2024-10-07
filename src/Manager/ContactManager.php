<?php 

namespace App\Manager; 

use App\Entity\Contact;
use App\Event\ContactEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ContactManager
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
     * Méthode pour persister et sauvegarder un contact dans la base de données
     * et dispatcher un événement associé à ce contact.
     * 
     * @param Contact $Contact - Le contact à persister
     */
    public function setContact($Contact)
    {
        // Vérifie si l'objet passé est bien une instance de la classe Contact
        if ($Contact instanceof Contact) {
            // Persiste le contact dans la base de données
            $this->em->persist($Contact);
            // Exécute la transaction pour sauvegarder le contact
            $this->em->flush();
            
            // Crée un nouvel événement en passant le contact comme paramètre
            $event = new ContactEvent($Contact);
            // Déclenche l'événement via l'EventDispatcher
            $this->eventDispatcherInterface->dispatch($event);
        }
    }
}