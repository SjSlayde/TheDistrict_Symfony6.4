<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactFormType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EntityManagerInterface $em,MailService $ms): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            $em->persist($contact);
            $em->flush();

            $ms->sendMailContact('hello@example.com', $contact->getEmail(), $contact->getEmail(), $contact->getDemande() );  

        return $this->redirectToRoute('app_index');
    } else {
        return $this->render('contact/index.html.twig',[
            'form' => $form
        ]);
    }
}
}
