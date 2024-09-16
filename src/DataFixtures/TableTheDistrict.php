<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Plat;
use App\Entity\Detail;
use App\Entity\Utilisateur;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;





class TableTheDistrict extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $cat1 = new Categorie();
        $cat1->setLibelle('hamburger');
        $cat1->setImage('burger_cat.jpg');
        $cat1->setActive(1);

        $manager->persist($cat1);

        $cat2 = new Categorie();
        $cat2->setLibelle('Pasta');
        $cat2->setImage('pasta_cat.jpg');
        $cat2->setActive(1);

        $manager->persist($cat2);

        $cat3 = new Categorie();
        $cat3->setLibelle('Pizza');
        $cat3->setImage('pizza_cat.jpg');
        $cat3->setActive(1);

        $manager->persist($cat3);

        $plat1 = new Plat();
        $plat1->setLibelle('District Burger');
        $plat1->setImage('hamburger.jpg');
        $plat1->setPrix(8.00);
        $plat1->setDescription('Burger composé d’un bun’s du boulanger, deux steaks de 80g (origine française), de deux tranches poitrine de porc fumée, de deux tranches cheddar affiné, salade et oignons confits.');
        $plat1->setActive(1);
        $plat1->setCategorie($cat1);

        $manager->persist($plat1);

        $plat2 = new Plat();
        $plat2->setLibelle('Cheeseburger');
        $plat2->setImage('cheesburger.jpg');
        $plat2->setPrix(8.00);
        $plat2->setDescription('Burger composé d’un bun’s du boulanger,de salade,oignons rouges, pickles,oignon confit,tomate,d’un steak d’origine Française,d’une tranche de cheddar affiné, et de notre sauce maison.');
        $plat2->setActive(1);
        $plat2->setCategorie($cat1);

        $manager->persist($plat2);

        $plat3 = new Plat();
        $plat3->setLibelle('Pizza Bianca');
        $plat3->setImage('pizza-salmon.png');
        $plat3->setPrix(14.00);
        $plat3->setDescription('Une pizza fine et croustillante garnie de crème mascarpone légèrement citronnée et de tranches de saumon fumé, le tout relevé de baies roses et de basilic frais.');
        $plat3->setActive(1);
        $plat3->setCategorie($cat3);

        $manager->persist($plat3);

        $plat4 = new Plat();
        $plat4->setLibelle('Pizza Margherita');
        $plat4->setImage('pizza-margherita.jpg');
        $plat4->setPrix(14.00);
        $plat4->setDescription('Une authentique pizza margarita, un classique de la cuisine italienne! Une pâte faite maison, une sauce tomate fraîche, de la mozzarella Fior di latte, du basilic, origan, ail, sucre, sel & poivre...');
        $plat4->setActive(1);
        $plat4->setCategorie($cat3);

        $manager->persist($plat4);

        $plat5 = new Plat();
        $plat5->setLibelle('Tagliatelles au saumon');
        $plat5->setImage('lasagnes_viande.jpg');
        $plat5->setPrix(12.00);
        $plat5->setDescription('Découvrez notre recette délicieuse de tagliatelles au saumon frais et à la crème qui qui vous assure un véritable régal!');
        $plat5->setActive(1);
        $plat5->setCategorie($cat2);

        $manager->persist($plat5);

        $user1 = new Utilisateur();
        $user1->setEmail('yop@gmail.com');
        $user1->setPassword('yop');

        $Password1 = 'yop';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user1,
            $Password1
        );
        $user1->setPassword($hashedPassword);
        $user1->setNom('Yop');
        $user1->setPrenom('Michele');
        $user1->setTelephone('0498825111');
        $user1->setAdresse('45 rue yopland');
        $user1->setCp('80000');
        $user1->setVille('Amiens');
        $user1->setRoles(['ROLE_ADMIN']);

        $manager->persist($user1);

        $user2 = new Utilisateur();
        $user2->setEmail('cacao@yahoo.com');
        // $user2->setPassword('cacao');

        $Password = 'cacao';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            $Password
        );
        $user2->setPassword($hashedPassword);
        
        $user2->setNom('Cacao');
        $user2->setPrenom('Bernard');
        $user2->setTelephone('0449452282');
        $user2->setAdresse('21 rue quatre cailloux');
        $user2->setCp('80000');
        $user2->setVille('Amiens');
        $user2->setRoles(['ROLE_CLIENT']);

        $manager->persist($user2);

        $commande1 = new Commande();
        $commande1->setDateCommande(new \DatetimeImmutable());
        $commande1->setTotal($plat3->getPrix());
        $commande1->setEtat(3);
        $commande1->setUtilisateurs($user1);

        $manager->persist($commande1);

        $commande2 = new Commande();
        $commande2->setDateCommande(new \DatetimeImmutable());
        $commande2->setTotal($plat1->getPrix() + $plat5->getPrix());
        $commande2->setEtat(2);
        $commande2->setUtilisateurs($user2);

        $manager->persist($commande2);

        $detail1 = new Detail();
        $detail1->setQuantite(1);
        $detail1->setCommandes($commande1);
        $detail1->setPlats($plat3);

        $manager->persist($detail1);

        $detail2 = new Detail();
        $detail2->setQuantite(1);
        $detail2->setCommandes($commande2);
        $detail2->setPlats($plat1);

        $manager->persist($detail2);

        $detail3 = new Detail();
        $detail3->setQuantite(1);
        $detail3->setCommandes($commande2);
        $detail3->setPlats($plat5);

        $manager->persist($detail3);

        $manager->flush();
    }

    // public function passhasher($password,$user, UserPasswordHasherInterface $passwordHasher):Response{
    //     $hashedPassword = $passwordHasher->hashPassword(
    //         $user,
    //         $password
    //     );
        
    //     return $user->setPassword($hashedPassword);
    // }
}
