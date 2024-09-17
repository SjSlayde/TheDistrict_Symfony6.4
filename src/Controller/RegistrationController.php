<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\CommandeRepository;
use App\Form\RegistrationFormType;
use App\Repository\DetailRepository;
use App\Repository\PlatRepository;
use App\Security\UserFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    private $utilisateurRepository;
    private $commandeRepository;
    private $detailsRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository,CommandeRepository $commandeRepository,DetailRepository $detailsRepository)
    {
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

            return $this->render('connexion/detail.html.twig',[
                'user'=> $user,
                'commandes'=> $commandes
            ]);
        }

        #[Route('/{nom}-{prenom}/edit', name: 'app_editprofil')]
        public function EditUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security,EntityManagerInterface $em): Response
        {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
            /** @var \App\Entity\Utilisateur $user */
            $user = $this->getUser();

            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();

                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                // $em->persist($user);
                $em->flush();

                // do anything else you need here, like send an email

                // return $security->login($user, UserFormAuthenticator::class, 'main');
                return $this->redirectToRoute('app_utilisateur' , [
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom()
                    ]);
                }

            return $this->render('connexion/edit.html.twig',[
                'user'=> $user,
                'form' => $form
            ]);
        }
}
