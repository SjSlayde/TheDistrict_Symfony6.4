<?php

namespace App\Entity;

use App\Repository\DetailRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups; 

#[ORM\Entity(repositoryClass: DetailRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[Groups(['read'])]
    private ?Plat $plats = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    #[Groups(['read'])]
    private ?Commande $commandes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getplats(): ?Plat
    {
        return $this->plats;
    }

    public function setplats(?Plat $plats): static
    {
        $this->plats = $plats;

        return $this;
    }

    public function getCommandes(): ?Commande
    {
        return $this->commandes;
    }

    public function setCommandes(?Commande $commandes): static
    {
        $this->commandes = $commandes;

        return $this;
    }
}
