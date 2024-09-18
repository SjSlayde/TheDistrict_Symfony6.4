<?php 

namespace App\EventSubscriber;
use App\Event\CommandeEvent;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    private $mailservice;

    public function __construct(MailService $mailservice){
        $this->mailservice = $mailservice;
    }

    public function SendMailEvent(CommandeEvent $event){
        $commande = $event->getCommande();
        $this->mailservice->sendMailCommande('yop@michel.fr' ,$commande,$commande->getUtilisateurs());
    }

    public static function getSubscribedEvents(): array{
        return[
            CommandeEvent::class => [
                ['SendMailEvent',1]
            ]
        ];
    }
}