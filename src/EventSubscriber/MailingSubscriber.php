<?php 

namespace App\EventSubscriber;
use App\Event\CommandeEvent;
use App\Event\ContactEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    private $mailservice;

    public function __construct(MailService $mailservice){
        $this->mailservice = $mailservice;
    }
    
    public function SendMailEventCommande(CommandeEvent $event){
    $commande = $event->getCommande();

    $parameters = [
        "user" => $commande->getUtilisateurs(),
        "commande" => $commande,
        "datejour" => date("d-m-Y"),
        "dateheure" => date("H:m"),
        "datelivraison" => date('H:i:s', strtotime('+30 minutes', strtotime(date('H:i:s'))))
    ];

        $this->mailservice->sendMailCommande(
            'yop@michel.fr' ,
            $commande->getUtilisateurs(),
            'Commande N°'.$commande->getId(),
            CommandeEvent::TEMPLATE_COMMANDE,
            $parameters);
    }

    public function SendMailEventContact(ContactEvent $event){
        $contact = $event->getContact();
        // (string $emailpros ,string $emailclient, string $Nom,string $template,array $parameters)
    
        $parameters = [
            "contact" => $contact,
            "Demande" => $contact->getDemande(),
        ];

            $this->mailservice->sendMailContact(
                'yop@michel.fr' ,
                $contact->getEmail(),
                $contact->getNom(),
                ContactEvent::TEMPLATE_Contact,
                $parameters);}

    public static function getSubscribedEvents(): array{
        return[
            CommandeEvent::class => [
                ['SendMailEventCommande',1]
            ],
            ContactEvent::class => [
                ['SendMailEventContact',1]
            ]
        ];
    }
}