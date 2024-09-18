<?php 

namespace App\Event;
use App\Entity\Commande;
use Symfony\Contracts\EventDispatcher\Event;
class CommandeEvent extends Event
{
    private $commande;
    public function __construct(Commande $commande){
        $this->commande = $commande;
    }

    public function getCommande(): Commande{
        return $this->commande;
    }
}