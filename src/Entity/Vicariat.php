<?php

namespace App\Entity;

use App\Repository\VicariatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VicariatRepository::class)]
class Vicariat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Doyenne::class, mappedBy: 'vicariat')]
    private Collection $doyennes;

    public function __construct()
    {
        $this->doyennes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Doyenne>
     */
    public function getDoyennes(): Collection
    {
        return $this->doyennes;
    }

    public function addDoyenne(Doyenne $doyenne): static
    {
        if (!$this->doyennes->contains($doyenne)) {
            $this->doyennes->add($doyenne);
            $doyenne->setVicariat($this);
        }

        return $this;
    }

    public function removeDoyenne(Doyenne $doyenne): static
    {
        if ($this->doyennes->removeElement($doyenne)) {
            // set the owning side to null (unless already changed)
            if ($doyenne->getVicariat() === $this) {
                $doyenne->setVicariat(null);
            }
        }

        return $this;
    }


}
