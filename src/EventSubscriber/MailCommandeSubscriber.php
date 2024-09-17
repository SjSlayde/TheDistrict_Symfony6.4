<?php

namespace App\EventSubscriber;

use App\Entity\Commande;
use App\Entity\Detail;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;


class MailCommandeSubscriber implements EventSubscriber
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents()
    {
        //retourne un tableau d'événements (prePersist, postPersist, preUpdate etc...)
        return [
            //événement déclenché après l'insert dans la base de donnée
            Events::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
//        $args->getObject() nous retourne l'entité concernée par l'événement postPersist
        $entity = $args->getObject();
        dump($entity);

//     Vérifier si l'entité est un nouvel objet de type Commande;
//    Si l'objet persité n'est pas de type Commande, on ne veut pas que le Subscriber se déclenche!
        if ($entity instanceof Commande) {

            $objet = $entity->getDetails();
            // $message = $entity->getMessage();

            //Si l'objet ou le text du message contiennent le mot "rgpd", le Subscriber enverra un email à l'adresse "admin@velvet.com"
            if ($objet < 3)  {
                //     Envoyer un e-mail à l'admin
                $email = (new Email())
                    ->from('votre_adresse_email@example.com')
                    ->to('admin@velvet.com')
                    ->subject('Alerte RGPD')
                    ->text("Un nouveau message en rapport avec la loi sur les RGPD vous a été envoyé! L'id du message : " .$entity->getId());

                $this->mailer->send($email);
            }

        }
    }
}