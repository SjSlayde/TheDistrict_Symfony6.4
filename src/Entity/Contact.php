<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource; 
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
// #[ApiResource(
//     normalizationContext: ['groups' => ['read']],
//     denormalizationContext: ['groups' => ['write']],
// )]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    #[Constraints\NotBlank()]
    #[Constraints\Regex('/^[A-Za-zÀ-ÖØ-öø-ÿ\'-]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ\'-]+)*$/', message: 'Nom invalide, le champ ne peut contenir de Caractère Spéciaux')]
    private ?string $Nom = null;

    #[ORM\Column(length: 50)]
    #[Groups('read')]
    #[Constraints\NotBlank()]
    #[Constraints\Regex('/^[A-Za-zÀ-ÖØ-öø-ÿ\'-]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ\'-]+)*$/', message: 'Prenom invalide, le champ ne peut contenir de Caractère Spéciaux')]
    private ?string $Prenom = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups('read')]
    #[Constraints\NotBlank()]
    #[Assert\Email(
        message: 'l\'adresse email : {{ value }} n\'est pas valide.',
    )]
    private ?string $Email = null;

    #[ORM\Column(length: 20)]
    #[Groups('read')]
    #[Constraints\Regex('/(0|\\+33|0033)[1-9][0-9]{8}/', message: 'numero de telephone invalide')]
    #[Constraints\NotBlank()]
    private ?string $Telephone = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    #[Groups('read')]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 20,minMessage:'Votre demande doit contenir un minimum {{ limit }} caractere')]
    private ?string $Demande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): static
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    public function getDemande(): ?string
    {
        return $this->Demande;
    }

    public function setDemande(string $Demande): static
    {
        $this->Demande = $Demande;

        return $this;
    }
}
