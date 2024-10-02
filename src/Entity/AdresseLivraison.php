<?php

namespace App\Entity;

use App\Repository\AdresseLivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdresseLivraisonRepository::class)]
// #[ApiResource(
//     normalizationContext: ['groups' => ['read']],
//     denormalizationContext: ['groups' => ['write']],
// )]
class AdresseLivraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    private ?string $adresse = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    private ?string $cp = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'adresseLivraisons')]
    #[Groups('read')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
