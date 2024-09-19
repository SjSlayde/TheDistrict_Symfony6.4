<?php 

namespace App\Manager; 

use App\Entity\Commande;
use App\Event\CommandeEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommandeManager
{
    private $em;
    private $eventDispatcherInterface;

    public function __construct(EntityManagerInterface $em,EventDispatcherInterface $eventDispatcherInterface){
        $this->em = $em;
        $this->eventDispatcherInterface = $eventDispatcherInterface;
    }

    public function setCommande($commande){
        if($commande instanceof Commande){
            $this->em->persist($commande);
            $this->em->flush();
            $event = new CommandeEvent($commande);
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