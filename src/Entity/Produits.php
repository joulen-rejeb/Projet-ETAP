<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: 'id_produit', type: 'integer')]
private ?int $id = null;


    #[ORM\Column(name: 'name_product', length: 255)]
    private ?string $nameProduct = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(name: 'quantity_initial')]
    private ?int $quantityInitial = null;

    #[ORM\Column(name: 'date_added', type: 'datetime')]
    private ?\DateTimeInterface $dateAdded = null;

    #[ORM\Column(name: 'photo_product', length: 255, nullable: true)]
    private ?string $photoProduct = null;

    #[ORM\Column(name: 'user_id')]
    private ?int $userId = null;

   #[ORM\ManyToOne(targetEntity: Emplacement::class)]
#[ORM\JoinColumn(name: "emplacement_id", referencedColumnName: "id_emplacement", nullable: false)]
private ?Emplacement $emplacement = null;


   #[ORM\ManyToOne(targetEntity: Familles::class)]
   #[ORM\JoinColumn(name: "famille_id", referencedColumnName: "id_famille", nullable: false)]
   private ?Familles $familles = null;

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantityInitial(): ?int
    {
        return $this->quantityInitial;
    }

    public function setQuantityInitial(int $quantityInitial): self
    {
        $this->quantityInitial = $quantityInitial;
        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTimeInterface $dateAdded): self
    {
        $this->dateAdded = $dateAdded;
        return $this;
    }

    public function getPhotoProduct(): ?string
    {
        return $this->photoProduct;
    }

    public function setPhotoProduct(?string $photoProduct): self
    {
        $this->photoProduct = $photoProduct;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

  public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

      public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

     public function getFamilles(): ?Familles
    {
        return $this->familles;
    }

      public function setFamilles(?Familles $familles): self
    {
        $this->familles = $familles;
        return $this;
    }
 

}