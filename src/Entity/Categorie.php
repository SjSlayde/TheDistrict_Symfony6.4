<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),  
    //    new Put(),
    //    new Patch(),
    //    new Delete(),
        new GetCollection(),
    //    new Post(),
    ]
)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    #[Groups(['read'])]
    #[Constraints\NotBlank()]
    #[Constraints\Regex('/^[A-Za-zÀ-ÖØ-öø-ÿ\'-]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ\'-]+)*$/', message: 'libellé invalide, le champ ne peut contenir de Caractère Spéciaux')]
    private ?string $libelle = null;

    // #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    #[Groups(['read','write'])]
    #[Assert\Image()]
    private ?string $image = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?bool $active = null;

    /**
     * @var Collection<int, Plat>
     */
    
    #[ORM\OneToMany(targetEntity: Plat::class, mappedBy: 'plats')]
    #[Groups(['write'])]
    private Collection $plats;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setPlats($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getPlats() === $this) {
                $plat->setPlats(null);
            }
        }

        return $this;
    }
}
