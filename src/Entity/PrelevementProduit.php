<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prelevement_produit')]
class PrelevementProduit
{
     #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_prelevement_produit', type: 'integer')]
    private ?int $idPrelevementProduit = null;

// src/Entity/PrelevementProduit.php

#[ORM\ManyToOne(targetEntity: Prelevement::class, inversedBy: 'prelevementProduits')]
#[ORM\JoinColumn(
    name: "prelevement_id",  // Nom de colonne dans votre table
    referencedColumnName: "id_prelevement",  // Colonne référencée dans Prelevement
    nullable: false
)]
private ?Prelevement $prelevement = null;

#[ORM\ManyToOne(targetEntity: Produits::class)]
#[ORM\JoinColumn(
    name: "produit_id", 
    referencedColumnName: "id_produit",  // Spécifiez la colonne personnalisée
    nullable: false
)]
private ?Produits $produit = null;

    #[ORM\Column(type: "integer")]
    private ?int $quantite = null;

    // Getters / Setters
    public function getIdPrelevementProduit(): ?int { return $this->idPrelevementProduit; }
    public function getPrelevement(): ?Prelevement { return $this->prelevement; }
    public function setPrelevement(?Prelevement $prelevement): self { $this->prelevement = $prelevement; return $this; }
    public function getProduit(): ?Produits { return $this->produit; }
    public function setProduit(?Produits $produit): self { $this->produit = $produit; return $this; }
    public function getQuantite(): ?int { return $this->quantite; }
    public function setQuantite(int $quantite): self { $this->quantite = $quantite; return $this; }
}
