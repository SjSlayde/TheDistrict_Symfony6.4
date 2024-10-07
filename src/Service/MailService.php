<?php

namespace App\Service;

// Import des entités et services nécessaires
use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
     // Propriétés pour le mailer et Twig
    private $mailer;

    private $twig;

    // On injecte dans le constructeur le MailerInterface et l'environnement Twig
    public function __construct(MailerInterface $mailer, Environment $twig){
        // Initialisation du service d'envoi d'e-mails
        $this->mailer = $mailer;

        // Initialisation du moteur de templates Twig
        $this->twig = $twig;
    }

      /**
     * Méthode pour envoyer un e-mail de contact
     * 
     * @param string $emailpros - L'adresse e-mail de l'expéditeur (professionnel)
     * @param string $emailclient - L'adresse e-mail du destinataire (client)
     * @param string $Nom - Le sujet de l'e-mail
     * @param string $template - Le template Twig à utiliser pour l'e-mail
     * @param array $parameters - Les variables à injecter dans le template
     */
    public function sendMailContact(string $emailpros ,string $emailclient, string $Nom,string $template,array $parameters){
        
        // Création d'un nouvel objet Email de Symfony
        $email = (new Email())
        ->from($emailpros)// Définition de l'expéditeur

        ->to($emailclient)// Définition du destinataire
        ->subject($Nom)// Sujet de l'e-mail
        ->html(
            $this->twig->render($template, $parameters)
        );

        // Envoi de l'e-mail via le service mailer
        $this->mailer->send($email);
    }

     /**
     * Méthode pour envoyer un e-mail de commande
     * 
     * @param string $emailpros - L'adresse e-mail de l'expéditeur (professionnel)
     * @param Utilisateur $user - L'utilisateur qui recevra l'e-mail (pour récupérer son e-mail)
     * @param string $subject - Le sujet de l'e-mail
     * @param string $template - Le template Twig à utiliser pour l'e-mail
     * @param array $parameters - Les variables à injecter dans le template
     */
    public function sendMailCommande(string $emailpros,Utilisateur  $user,string $subject,
    string $template,array $parameters){
        
        // Création d'un nouvel objet Email de Symfony
        $email = (new Email())
        ->from($emailpros)// Définition de l'expéditeur

        ->to($user->getEmail())// Définition du destinataire
        ->subject($subject)// Sujet de l'e-mail
        ->html(
            $this->twig->render($template, $parameters)
        );

        // Envoi de l'e-mail via le service mailer
        $this->mailer->send($email);
    }
}