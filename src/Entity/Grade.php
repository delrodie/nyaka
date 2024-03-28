<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('participation')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    #[Groups('participation')]
    private ?int $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    #[Groups('participation')]
    private ?int $teeshirt = null;

    #[ORM\OneToMany(targetEntity: Aspirant::class, mappedBy: 'grade')]
    #[Groups('participation')]
    private Collection $aspirants;

    public function __construct()
    {
        $this->aspirants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): static
    {
        $this->montant = $montant;

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

    public function getTeeshirt(): ?int
    {
        return $this->teeshirt;
    }

    public function setTeeshirt(?int $teeshirt): static
    {
        $this->teeshirt = $teeshirt;

        return $this;
    }

    /**
     * @return Collection<int, Aspirant>
     */
    public function getAspirants(): Collection
    {
        return $this->aspirants;
    }

    public function addAspirant(Aspirant $aspirant): static
    {
        if (!$this->aspirants->contains($aspirant)) {
            $this->aspirants->add($aspirant);
            $aspirant->setGrade($this);
        }

        return $this;
    }

    public function removeAspirant(Aspirant $aspirant): static
    {
        if ($this->aspirants->removeElement($aspirant)) {
            // set the owning side to null (unless already changed)
            if ($aspirant->getGrade() === $this) {
                $aspirant->setGrade(null);
            }
        }

        return $this;
    }
}
