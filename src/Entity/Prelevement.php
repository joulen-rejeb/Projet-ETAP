<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prelevement')]
class Prelevement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_prelevement', type: 'integer')]
    private ?int $idPrelevement = null;

    #[ORM\Column(name: "request_date", type: "datetime")]
    private ?\DateTimeInterface $requestDate = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
#[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_utilisateur', nullable: false)]
private ?Utilisateurs $user = null;


    #[ORM\OneToMany(mappedBy: 'prelevement', targetEntity: PrelevementProduit::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $prelevementProduits;

    public function __construct()
    {
        $this->prelevementProduits = new ArrayCollection();
    }

    // Getters / Setters
    public function getIdPrelevement(): ?int { return $this->idPrelevement; }
    public function getRequestDate(): ?\DateTimeInterface { return $this->requestDate; }
    public function setRequestDate(\DateTimeInterface $requestDate): self { $this->requestDate = $requestDate; return $this; }
    public function getUser(): ?Utilisateurs { return $this->user; }
    public function setUser(?Utilisateurs $user): self { $this->user = $user; return $this; }

    /** @return Collection<int, PrelevementProduit> */
    public function getPrelevementProduits(): Collection { return $this->prelevementProduits; }

    public function addPrelevementProduit(PrelevementProduit $pp): self
    {
        if (!$this->prelevementProduits->contains($pp)) {
            $this->prelevementProduits->add($pp);
            $pp->setPrelevement($this);
        }
        return $this;
    }

    public function removePrelevementProduit(PrelevementProduit $pp): self
    {
        if ($this->prelevementProduits->contains($pp)) {
            $this->prelevementProduits->removeElement($pp);
            if ($pp->getPrelevement() === $this) {
                $pp->setPrelevement(null);
            }
        }
        return $this;
    }
}
