<?php

namespace App\Entity;

use App\Repository\MoyenPaiementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource; 

#[ORM\Entity(repositoryClass: MoyenPaiementRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class MoyenPaiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    // #[Assert\CardScheme(
    //     schemes: [Assert\CardScheme::MASTERCARD],
    //     message: 'Votre carte de paiement est invalide(Mastercard obligatoire)',
    // )]
    private ?string $numeros_de_carte = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    private ?string $expiration = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    private ?string $code_securite = null;

    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $nom_titulaire = null;

    #[ORM\ManyToOne(inversedBy: 'moyenPaiements')]
    #[Groups('read')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumerosDeCarte(): ?string
    {
        return $this->numeros_de_carte;
    }

    public function setNumerosDeCarte(string $numeros_de_carte): static
    {
        $this->numeros_de_carte = $numeros_de_carte;

        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function setExpiration(string $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getCodeSecurite(): ?string
    {
        return $this->code_securite;
    }

    public function setCodeSecurite(string $code_securite): static
    {
        $this->code_securite = $code_securite;

        return $this;
    }

    public function getNomTitulaire(): ?string
    {
        return $this->nom_titulaire;
    }

    public function setNomTitulaire(string $nom_titulaire): static
    {
        $this->nom_titulaire = $nom_titulaire;

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
