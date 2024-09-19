<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    private $mailer;

    private $twig;

    //On injecte dans le constructeur le MailerInterface

    public function __construct(MailerInterface $mailer, Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    //On crée une méthode pour envoyer un mail
    public function sendMailContact(string $emailpros ,string $emailclient, string $Nom,string $template,array $parameters){
        
        $email = (new Email())
        ->from($emailpros)

        ->to($emailclient)
        ->subject($Nom)
        ->html(
            $this->twig->render($template, $parameters)
        );


        $this->mailer->send($email);
    }

    public function sendMailCommande(string $emailpros,Utilisateur  $user,string $subject,
    string $template,array $parameters){
        
        $email = (new Email())
        ->from($emailpros)

        ->to($user->getEmail())
        ->subject($subject)
        ->html(
            $this->twig->render($template, $parameters)
        );

        $this->mailer->send($email);
    }
}