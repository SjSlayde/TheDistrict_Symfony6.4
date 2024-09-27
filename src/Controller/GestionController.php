<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Plat;
use App\Form\CategorieFormType;
use App\Form\PlatFormType;
use App\Repository\CategorieRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GestionController extends AbstractController
{

    private $platRepo;
    private $categoryRepo;
    private $em;

    public function __construct(CategorieRepository $categoryRepo, PlatRepository $platRepo,EntityManagerInterface $em)
    {
        $this->categoryRepo = $categoryRepo;
        $this->platRepo = $platRepo;
        $this->em = $em;

    }

    #[Route('/gestion', name: 'app_gestion')]
    public function gestion(): Response
    {

        $category = $this->categoryRepo->findAll();
        $plat = $this->platRepo->findAll();
    
        return $this->render('gestion/index.html.twig', [
            'controller_name' => 'CatalogueController',
            'category' => $category,
            'plat' => $plat
        ]);
    }

    #[Route('/gestion/plat-{id}', name: 'app_gestion_plat')]
    public function gestionPlat(Request $request,$id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHEF');

        if(is_null($this->platRepo->find($id))){
            $plat = new Plat();
            $titre = 'Nouveau Plat';
        } else {
            $plat = $this->platRepo->find($id);
            $titre = 'modifier le plat '.$plat->getLibelle();
        }
    
        $form = $this->createForm(PlatFormType::class, $plat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($plat);
            $this->em->flush();
    
            return $this->redirectToRoute('app_gestion');
        } else {
        return $this->render('gestion/gestionplat.html.twig', [
            'titre' => $titre,
            'form' => $form
        ]);}
    }

    #[Route('/gestion/categorie-{id}', name: 'app_gestion_categorie')]
    public function gestionCategorie(Request $request,$id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CHEF');

        if(is_null($this->categoryRepo->find($id))){
            $cat = new Categorie();
            $titre = 'Nouvelle Categorie';
        } else {
            $cat = $this->categoryRepo->find($id);
            $titre = 'modifier la Categorie '.$cat->getLibelle();
        }
    
        $form = $this->createForm(CategorieFormType::class, $cat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($cat);
            $this->em->flush();
    
            return $this->redirectToRoute('app_gestion');
        } else {
        return $this->render('gestion/gestioncat.html.twig', [
            'titre' => $titre,
            'form' => $form
        ]);}
    }
}
