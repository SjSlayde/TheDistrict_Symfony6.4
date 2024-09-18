<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    //On injecte dans le constructeur le MailerInterface

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    //On crée une méthode pour envoyer un mail
    public function sendMailContact(string $emailpros ,string $emailclient, string $Nom, string $Demande){

        $text = "";
        
        $email = (new Email())
        ->from($emailpros)

        ->to($emailclient)
        ->subject($Nom)
        ->text($Demande);

        $this->mailer->send($email);
    }

    public function sendMailCommande(string $emailpros,Commande $commande,Utilisateur  $user){

        $texte  =  "Objet : Confirmation de votre commande

    Chère/Cher ".$user->getNom().",
    
    Nous vous remercions pour votre récente commande sur The District. Nous sommes ravis de vous informer que votre commande a été bien reçue et est actuellement en cours de traitement.
    Détails de la commande :
    
        Nom du client : ".$user->getNom()."
        Adresse de livraison : ".$user->getAdresse()."
        Date de commande : le ".date("d-m-Y")." a ".date("H:m")."
    
    Étapes suivantes :
    
        Préparation de votre commande : Nos équipes sont en train de préparer soigneusement vos articles pour garantir leur fraîcheur et leur qualité.
        Expédition : Votre commande sera expédiée sous 15min .
        Livraison : La livraison est estimée pour le ".date('H:i:s', strtotime('+30 minutes', strtotime(date('H:i:s')))).".
    
    Paiement :
    
    Le montant total de votre commande est de ".$commande->getTotal()." euros, qui a été débité de votre mode de paiement sélectionné.
    
    Si vous avez des questions ou besoin d'assistance supplémentaire, n'hésitez pas à nous contacter à reply@thedistrict.com . Notre équipe est à votre disposition pour vous aider.
    
    Nous vous remercions de votre confiance et espérons que vous apprécierez vos produits !
    
    Cordialement,
    
    The District";
        
        $email = (new Email())
        ->from($emailpros)

        ->to($user->getEmail())
        ->subject($user->getNom())
        ->text($texte);

        $this->mailer->send($email);
    }
}