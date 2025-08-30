<?php

namespace App\Entity;

use App\Repository\EmplacementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\Produits;


#[ORM\Entity(repositoryClass: EmplacementRepository::class)]
class Emplacement
{
 #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: "integer", name: "id_emplacement")]
private ?int $idEmplacement = null;


    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

     #[ORM\Column(name: 'photo_emplacement', length: 255, nullable: true)]
    private ?string $photoEmplacement	 = null;

 // Ajout de la relation OneToMany vers Produits
    #[ORM\OneToMany(mappedBy: "emplacement", targetEntity: Produits::class)]
    private Collection $produits;

    public function getIdEmplacement(): ?int
{
    return $this->idEmplacement;
}

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): self
    {
        $this->zone = $zone;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    
    public function getPhotoEmplacement(): ?string
    {
        return $this->photoEmplacement;
    }

    public function setPhotoEmplacement(?string $photoEmplacement): self
    {
        $this->photoEmplacement = $photoEmplacement;
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
