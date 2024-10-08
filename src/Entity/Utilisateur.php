<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups; 
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
// #[ApiResource(
//     security: "is_granted('ROLE_ADMIN')",
//     normalizationContext: ['groups' => ['read']],
//     denormalizationContext: ['groups' => ['write']],
// )]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Assert\Email(
        message: 'l\'adresse email : {{ value }} n\'est pas valide.',
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 8)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Constraints\Regex('/^[A-Za-zÀ-ÖØ-öø-ÿ\'-]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ\'-]+)*$/', message: 'Nom invalide, le champ ne peut contenir de Caractère Spéciaux')]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Constraints\Regex('/^[A-Za-zÀ-ÖØ-öø-ÿ\'-]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ\'-]+)*$/', message: 'Prenom invalide, le champ ne peut contenir de Caractère Spéciaux')]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    #[Groups(['read'])]
    #[Constraints\Regex('/(0|\\+33|0033)[1-9][0-9]{8}/', message: 'numero de telephone invalide')]
    #[Constraints\NotBlank()]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    private ?string $adresse = null;

    #[ORM\Column(length: 20)]
    #[Groups(['read'])]
    #[Constraints\Length(max: 5)]
    #[Constraints\Regex('/[0-9]{5}/', message: 'Code postal invalide')]
    #[Constraints\NotBlank()]
    private ?string $cp = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Constraints\Length(min: 1)]
    private ?string $ville = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'utilisateur')]
    #[Groups(['write'])]
    private Collection $commande;

    /**
     * @var Collection<int, AdresseLivraison>
     */
    #[ORM\OneToMany(targetEntity: AdresseLivraison::class, mappedBy: 'utilisateur')]
    #[Groups(['write'])]
    private Collection $adresseLivraisons;

    /**
     * @var Collection<int, MoyenPaiement>
     */
    #[ORM\OneToMany(targetEntity: MoyenPaiement::class, mappedBy: 'utilisateur')]
    #[Groups(['write'])]
    private Collection $moyenPaiements;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->adresseLivraisons = new ArrayCollection();
        $this->moyenPaiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AdresseLivraison>
     */
    public function getAdresseLivraisons(): Collection
    {
        return $this->adresseLivraisons;
    }

    public function addAdresseLivraison(AdresseLivraison $adresseLivraison): static
    {
        if (!$this->adresseLivraisons->contains($adresseLivraison)) {
            $this->adresseLivraisons->add($adresseLivraison);
            $adresseLivraison->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdresseLivraison(AdresseLivraison $adresseLivraison): static
    {
        if ($this->adresseLivraisons->removeElement($adresseLivraison)) {
            // set the owning side to null (unless already changed)
            if ($adresseLivraison->getUtilisateur() === $this) {
                $adresseLivraison->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MoyenPaiement>
     */
    public function getMoyenPaiements(): Collection
    {
        return $this->moyenPaiements;
    }

    public function addMoyenPaiement(MoyenPaiement $moyenPaiement): static
    {
        if (!$this->moyenPaiements->contains($moyenPaiement)) {
            $this->moyenPaiements->add($moyenPaiement);
            $moyenPaiement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeMoyenPaiement(MoyenPaiement $moyenPaiement): static
    {
        if ($this->moyenPaiements->removeElement($moyenPaiement)) {
            // set the owning side to null (unless already changed)
            if ($moyenPaiement->getUtilisateur() === $this) {
                $moyenPaiement->setUtilisateur(null);
            }
        }

        return $this;
    }
}
