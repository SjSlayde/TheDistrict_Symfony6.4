<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Form\CommandeType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    private $PlatRepo;

    public function __construct(PlatRepository $PlatRepo){
        $this->PlatRepo = $PlatRepo;
    }

    #[Route('/commande', name: 'app_commande')]    
    public function index(Request $request,EntityManagerInterface $em,SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $form = $this->createForm(CommandeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $panier = $session->get('panier', []);

            $dataPanier = [];
            $total = 0;

            foreach($panier as $id => $quantite){
                $plat = $this->PlatRepo->find($id);
                $total += $plat->getPrix() * $quantite;
            }

            $commande = new Commande();
            $commande->setDateCommande(new \DatetimeImmutable());
            $commande->setTotal($total);
            $commande->setEtat(0);
            $commande->setUtilisateurs($user);

            $em->persist($commande);

            foreach($panier as $id => $quantite){
                $plat = $this->PlatRepo->find($id);

                $detail = new Detail();
                $detail->setQuantite($quantite);
                $detail->setCommandes($commande);
                $detail->setPlats($plat);

                $em->persist($detail);

                $em->flush();

                $total += $plat->getPrix() * $quantite;
            }

        return $this->redirectToRoute('app_index');
    } else {
        return $this->render('commande/index.html.twig',[
            'form' => $form
        ]);
    }
}}