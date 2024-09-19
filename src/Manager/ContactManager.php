<?php 

namespace App\Manager; 

use App\Entity\Contact;
use App\Event\ContactEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ContactManager
{
    private $em;
    private $eventDispatcherInterface;

    public function __construct(EntityManagerInterface $em,EventDispatcherInterface $eventDispatcherInterface){
        $this->em = $em;
        $this->eventDispatcherInterface = $eventDispatcherInterface;
    }

    public function setContact($Contact){
        if($Contact instanceof Contact){
            $this->em->persist($Contact);
            $this->em->flush();
            $event = new ContactEvent($Contact);
            $this->eventDispatcherInterface->dispatch($event);
        }
    }
}