<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]    
    public function index(Request $request,EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommandeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            
            // $em->persist($contact);
            // $em->flush();

        return $this->redirectToRoute('app_index');
    } else {
        return $this->render('commande/index.html.twig',[
            'form' => $form
        ]);
    }
}}