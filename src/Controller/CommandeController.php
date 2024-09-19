<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Manager\CommandeManager;
use App\Form\CommandeType;
use App\Manager\DetailManager;
use App\Repository\PlatRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    private $PlatRepo;
    private $ps;
    private $cm;
    private $dm;

    public function __construct(PlatRepository $PlatRepo, PanierService $panierService,CommandeManager $cm,DetailManager $dm){
        $this->PlatRepo = $PlatRepo;
        $this->ps = $panierService;
        $this->cm = $cm;
        $this->dm = $dm;
    }

    #[Route('/commande', name: 'app_commande')]    
    public function index(Request $request,EntityManagerInterface $em): Response
    {
        $panier = $this->ps->ShowPanier();

        if(!empty($panier)){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $form = $this->createForm(CommandeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $total = $this->ps->getTotal();

            $commande = new Commande();
            $commande->setDateCommande(new \DatetimeImmutable());
            $commande->setTotal($total);
            $commande->setEtat(0);
            $commande->setUtilisateurs($user);

            $this->cm->setCommande($commande);

            foreach($panier as $id => $quantite){
                $plat = $this->PlatRepo->find($id);

                $detail = new Detail();
                $detail->setQuantite($quantite);
                $detail->setCommandes($commande);
                $detail->setPlats($plat);

                $this->dm->setDetail($detail);
            }

            $this->ps->DeleteAllDish();

            return $this->redirectToRoute('app_index');
    } else {
        return $this->render('commande/index.html.twig',[
            'form' => $form
        ]);
    }        }else {
        return $this->redirectToRoute('app_panier');
    }
}}