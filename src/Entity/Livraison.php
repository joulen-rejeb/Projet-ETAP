<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produits;
use App\Entity\Utilisateurs;

#[ORM\Entity]
#[ORM\Table(name: "livraison")]
class Livraison
{
    #[ORM\Id]
    #[ORM\Column(name:"id_livraison", type:"integer")]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Produits::class)]
    #[ORM\JoinColumn(name:"product_id", referencedColumnName:"id_produit", nullable:false)]
    private ?Produits $product = null;

    #[ORM\Column(type:"integer")]
    private int $quantity = 0;

    #[ORM\Column(type:"datetime", name:"entry_date")]
    private \DateTimeInterface $entryDate;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id_utilisateur", nullable:false)]
    private ?Utilisateurs $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Produits
    {
        return $this->product;
    }

    public function setProduct(?Produits $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getEntryDate(): \DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): self
    {
        $this->entryDate = $entryDate;
        return $this;
    }

    public function getUser(): ?Utilisateurs
    {
        return $this->user;
    }

    public function setUser(?Utilisateurs $user): self
    {
        $this->user = $user;
        return $this;
    }
}
