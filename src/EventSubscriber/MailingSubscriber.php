<?php 

namespace App\EventSubscriber;

use App\Event\CommandeEvent;
use App\Event\ContactEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    private $mailservice;  // Propriété pour le service de messagerie (MailService)

    /**
     * Constructeur pour injecter le service MailService
     * 
     * @param MailService $mailservice - Le service utilisé pour envoyer des e-mails
     */
    public function __construct(MailService $mailservice)
    {
        // Initialisation du service de messagerie
        $this->mailservice = $mailservice;
    }

    /**
     * Méthode pour envoyer un e-mail suite à l'événement CommandeEvent
     * 
     * @param CommandeEvent $event - L'événement contenant les informations de la commande
     */
    public function SendMailEventCommande(CommandeEvent $event)
    {
        // Récupère la commande associée à l'événement
        $commande = $event->getCommande();

        // Prépare les paramètres à injecter dans le modèle d'e-mail
        $parameters = [
            "user" => $commande->getUtilisateurs(),
            "commande" => $commande,
            "details" => $commande->getDetails(),
            "datejour" => date("d-m-Y"),
            "dateheure" => date("H:i"),
            "datelivraison" => date('H:i:s', strtotime('+30 minutes', strtotime(date('H:i:s')))) // Livraison estimée dans 30 minutes
        ];

        // Envoi de l'e-mail pour la commande
        $this->mailservice->sendMailCommande(
            'yop@michel.fr',  // Adresse de l'expéditeur
            $commande->getUtilisateurs(),  // Destinataire (utilisateur lié à la commande)
            'Commande N°' . $commande->getId(),  // Sujet de l'e-mail
            CommandeEvent::TEMPLATE_COMMANDE,  // Modèle d'e-mail à utiliser
            $parameters  // Paramètres du modèle d'e-mail
        );
    }

    /**
     * Méthode pour envoyer un e-mail suite à l'événement ContactEvent
     * 
     * @param ContactEvent $event - L'événement contenant les informations du contact
     */
    public function SendMailEventContact(ContactEvent $event)
    {
        // Récupère le contact associé à l'événement
        $contact = $event->getContact();

        // Prépare les paramètres à injecter dans le modèle d'e-mail
        $parameters = [
            "contact" => $contact,
            "Demande" => $contact->getDemande(),
        ];

        // Envoi de l'e-mail pour le contact
        $this->mailservice->sendMailContact(
            'yop@michel.fr',  // Adresse de l'expéditeur
            $contact->getEmail(),  // Destinataire (adresse e-mail du contact)
            $contact->getNom(),  // Sujet de l'e-mail (nom du contact)
            ContactEvent::TEMPLATE_Contact,  // Modèle d'e-mail à utiliser
            $parameters  // Paramètres du modèle d'e-mail
        );
    }

    /**
     * Méthode statique pour indiquer quels événements sont écoutés par ce subscriber
     * 
     * @return array - Tableau associant les événements à leurs méthodes de gestion
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // Associe l'événement CommandeEvent à la méthode SendMailEventCommande
            CommandeEvent::class => [
                ['SendMailEventCommande', 1]  // Priorité 1
            ],
            // Associe l'événement ContactEvent à la méthode SendMailEventContact
            ContactEvent::class => [
                ['SendMailEventContact', 2]  // Priorité 2
            ]
        ];
    }
}