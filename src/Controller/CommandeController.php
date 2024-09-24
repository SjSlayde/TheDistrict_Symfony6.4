<?php

namespace App\Controller;

use App\Entity\AdresseLivraison;
use App\Entity\Commande;
use App\Entity\Detail;
use App\Entity\MoyenPaiement;
use App\Form\AdresseLivraisonType;
use App\Form\Formtest;
use App\Form\MoyenPaimentFormType;
use App\Manager\CommandeManager;
use App\Form\CommandeType;
use App\Manager\DetailManager;
use App\Repository\AdresseLivraisonRepository;
use App\Repository\MoyenPaiementRepository;
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

    private $MPayRepo;
    private $AdresseLivraisonRepo;
    private $ps;
    private $cm;
    private $dm;

    private $em;
    public function __construct(PlatRepository $PlatRepo, PanierService $panierService,
                                CommandeManager $cm,DetailManager $dm,
                                MoyenPaiementRepository $MPayRepo,AdresseLivraisonRepository $AdresseLivraisonRepo,
                                EntityManagerInterface $em){
        $this->PlatRepo = $PlatRepo;
        $this->MPayRepo = $MPayRepo;
        $this->AdresseLivraisonRepo = $AdresseLivraisonRepo;
        $this->ps = $panierService;
        $this->cm = $cm;
        $this->dm = $dm;
        $this->em = $em;
    }

   #[Route( '/commandeLivraison', name: 'app_commandeLivraison')]
   public function commandeLivraison(Request $request,SessionInterface $session): Response{
    $panier = $this->ps->ShowPanier();

    if(!empty($panier) && !$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){

    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    /** @var \App\Entity\Utilisateur $user */
    $user = $this->getUser();
    if($this->AdresseLivraisonRepo->findOneBy(["utilisateur" => $user->getId()])){
        $adresse = $this->AdresseLivraisonRepo->findOneBy(["utilisateur" => $user]);
    } else {
        $adresse = new AdresseLivraison();
    }

    $formAdresse= $this->createForm(AdresseLivraisonType::class,$adresse);
    $formAdresse->handleRequest($request);


    if ($formAdresse->isSubmitted() && $formAdresse->isValid()){
        $adresse->setUtilisateur($user);
        $this->em->persist($adresse);
        $this->em->flush();

        return $this->redirectToRoute('app_commandeFacturation');
    } else {
    return $this->render('commande/adresse.html.twig',[
        'formAdresse' => $formAdresse,
        'titre'=> 'Adresse de livraison'
    ])
    ;}} else {
    return $this->redirectToRoute('app_panier');
}}

#[Route( '/commande_adresse_facturation', name: 'app_commandeFacturation')]
   public function commandeFacturation(Request $request,SessionInterface $session): Response{
    $panier = $this->ps->ShowPanier();

    if(!empty($panier) && !$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){

    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    /** @var \App\Entity\Utilisateur $user */
    $user = $this->getUser();

    $formAdresse= $this->createForm(CommandeType::class,$user);
    $formAdresse->handleRequest($request);


    if ($formAdresse->isSubmitted() && $formAdresse->isValid()){
        $this->em->persist($user);
        $this->em->flush();

        return $this->redirectToRoute('app_commandePayment');
    } else {
    return $this->render('commande/adresse.html.twig',[
        'formAdresse' => $formAdresse,
        'titre'=> 'Adresse de facturation'
    ])
    ;}} else {
    return $this->redirectToRoute('app_panier');
}}

    #[Route('/commandePayment', name: 'app_commandePayment')]    
    public function index(Request $request): Response
    {
        $panier = $this->ps->ShowPanier();

        if(!empty($panier) && !$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        if($this->MPayRepo->findOneBy(["utilisateur" => $user->getId()])){
            $Paiement = $this->MPayRepo->findOneBy(["utilisateur" => $user]);
        } else {
            $Paiement = new MoyenPaiement();
        }

        $formPaiement= $this->createForm(MoyenPaimentFormType::class,$Paiement);
        $formPaiement->handleRequest($request);


        if ($formPaiement->isSubmitted() && $formPaiement->isValid()){
            $Paiement->setUtilisateur($user);
            $this->em->persist($Paiement);
            $this->em->flush();

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

            $this->addFlash('success','Vous allez être livré sous peu');

            return $this->redirectToRoute('app_index');
    } else {
        return $this->render('commande/paiement.html.twig',[
            'formPaiement' => $formPaiement,
        ]);
    }        }else {
        return $this->redirectToRoute('app_panier');
    }
}}