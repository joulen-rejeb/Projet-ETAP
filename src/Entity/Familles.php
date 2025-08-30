<?php

namespace App\Entity;

use App\Repository\FamillesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Produits;

#[ORM\Entity(repositoryClass: FamillesRepository::class)]
#[ORM\Table(name: 'familles')] // facultatif si le nom de la table est différent de la classe
class Familles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "id_famille")]
    private ?int $idFamilles = null;

    #[ORM\Column(name: 'name_category', type: 'string', length: 255)]
    private ?string $nameCategory = null;

    #[ORM\Column(name: 'photo_famille', length: 255, nullable: true)]
    private ?string $photoFamille = null;

    // Ajout de la relation OneToMany vers Produits
    #[ORM\OneToMany(mappedBy: "familles", targetEntity: Produits::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getIdFamilles(): ?int
    {
        return $this->idFamilles;
    }

    public function getNameCategory(): ?string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(string $nameCategory): self
    {
        $this->nameCategory = $nameCategory;
        return $this;
    }

    public function getPhotoFamille(): ?string
    {
        return $this->photoFamille;
    }

    public function setPhotoFamille(?string $photoFamille): self
    {
        $this->photoFamille = $photoFamille;
        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }
}
