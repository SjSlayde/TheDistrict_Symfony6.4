<?php

namespace App\Controller;

use App\Entity\AdresseLivraison;
use App\Entity\MoyenPaiement;
use App\Entity\Utilisateur;
use App\Form\MoyenPaimentFormType;
use App\Repository\AdresseLivraisonRepository;
use App\Repository\CommandeRepository;
use App\Form\RegistrationFormType;
use App\Form\AdresseLivraisonType;
use App\Repository\DetailRepository;
use App\Repository\MoyenPaiementRepository;
use App\Security\UserFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UtilisateurRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class RegistrationController extends AbstractController
{
    private $utilisateurRepository;
    private $commandeRepository;
    private $detailsRepository;

    public function __construct(
        UtilisateurRepository $utilisateurRepository,
        CommandeRepository $commandeRepository,
        DetailRepository $detailsRepository
    ) {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->commandeRepository = $commandeRepository;
        $this->detailsRepository = $detailsRepository;
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Votre compte client a biern été crées');
            return $security->login($user, UserFormAuthenticator::class, 'main');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/{nom}-{prenom}', name: 'app_utilisateur')]
    public function DetailsUser(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        $commandes = $this->commandeRepository->findBy(['utilisateur' => $user->getId()]);

        return $this->render('connexion/detail.html.twig', [
            'user' => $user,
            'commandes' => $commandes
        ]);
    }

    #[Route('/{nom}-{prenom}/edit', name: 'app_editprofil')]
    public function EditUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // $em->persist($user);
            $em->flush();

            // do anything else you need here, like send an email

            $this->addFlash('success', 'Vos informations personnelles ont été changées');
            // return $security->login($user, UserFormAuthenticator::class, 'main');
            return $this->redirectToRoute('app_utilisateur', [
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ]);
        }

        return $this->render('connexion/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/{nom}-{prenom}/nouvelle_adresse', name: 'app_newadresse')]
    public function newAdresse(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $adresse = new AdresseLivraison();

        $form = $this->createForm(AdresseLivraisonType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $adresse->setUtilisateur($user);

            $em->persist($adresse);
            $em->flush();

            $this->addFlash('success', 'Votre adresse de livraison a été ajoutées');

            return $this->redirectToRoute('app_utilisateur', [
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ]);
        }

        return $this->render('connexion/NewAdresse.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{nom}-{prenom}/modifier_adresse-{id}', name: 'app_editadresse', requirements: ['id' => '\d+'])]
    public function editAdresse(Request $request, EntityManagerInterface $em, AdresseLivraisonRepository $adresseLivraisonRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $adresse = $adresseLivraisonRepository->find($id);

        $form = $this->createForm(AdresseLivraisonType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $adresse->setUtilisateur($user);

            $em->persist($adresse);
            $em->flush();

            $this->addFlash('success', 'Votre adresse de livraison a été changées');

            return $this->redirectToRoute('app_utilisateur', [
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ]);
        }

        return $this->render('connexion/NewAdresse.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{nom}-{prenom}/remove_adresse-{id}', name: 'app_suppadresse', requirements: ['id' => '\d+'])]
    public function removeAdresse(Request $request, EntityManagerInterface $em, AdresseLivraisonRepository $adresseLivraisonRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $adresse = $adresseLivraisonRepository->find($id);
        $user = $this->getUser();

        $em->remove($adresse);
        $em->flush();

        $this->addFlash('success', 'Votre adresse de livraison a été supprimer');

        return $this->redirectToRoute('app_utilisateur', [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom()
        ]);
    }

    #[Route('/{nom}-{prenom}/moyen_paiement', name: 'app_newmoyenpaiement')]
    public function newmoyenpaiement(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $MP = new MoyenPaiement();

        $form = $this->createForm(MoyenPaimentFormType::class, $MP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $MP->setUtilisateur($user);

            $em->persist($MP);
            $em->flush();

            $this->addFlash('success', 'Votre moyen de paiement a été ajoutées');

            return $this->redirectToRoute('app_utilisateur', [
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ]);
        }

        return $this->render('connexion/NewMoyendepaiment.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{nom}-{prenom}/modifier_moyen_paiement-{id}', name: 'app_editmoyenpaiement', requirements: ['id' => '\d+'])]
    public function editMoyenPaiement(Request $request, EntityManagerInterface $em, MoyenPaiementRepository $moyenPaiementRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $MP = $moyenPaiementRepository->find($id);

        $form = $this->createForm(MoyenPaimentFormType::class, $MP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $MP->setUtilisateur($user);

            $em->persist($MP);
            $em->flush();

            $this->addFlash('success', 'Votre Moyen de paiment a été changées');

            return $this->redirectToRoute('app_utilisateur', [
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom()
            ]);
        }

        return $this->render('connexion/NewMoyendepaiment.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{nom}-{prenom}/remove_moyen_paiemen-{id}', name: 'app_suppmoyenpaiement', requirements: ['id' => '\d+'])]
    public function removemoyenpaiemen(
        Request $request,
        EntityManagerInterface $em,
        MoyenPaiementRepository $moyenPaiementRepository,
        int $id
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $MP = $moyenPaiementRepository->find($id);
        $user = $this->getUser();

        $em->remove($MP);
        $em->flush();

        $this->addFlash('success', 'Votre moyen de paiement a été supprimer');

        return $this->redirectToRoute('app_utilisateur', [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom()
        ]);
    }
}
