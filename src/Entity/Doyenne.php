<?php

namespace App\Entity;

use App\Repository\DoyenneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DoyenneRepository::class)]
class Doyenne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('participation')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups('participation')]
    private ?int $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'doyennes')]
    #[Groups('participation')]
    private ?Vicariat $vicariat = null;

    #[ORM\OneToMany(targetEntity: Section::class, mappedBy: 'doyenne')]
    private Collection $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
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

    public function getVicariat(): ?Vicariat
    {
        return $this->vicariat;
    }

    public function setVicariat(?Vicariat $vicariat): static
    {
        $this->vicariat = $vicariat;

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): static
    {
        if (!$this->sections->contains($section)) {
            $this->sections->add($section);
            $section->setDoyenne($this);
        }

        return $this;
    }

    public function removeSection(Section $section): static
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getDoyenne() === $this) {
                $section->setDoyenne(null);
            }
        }

        return $this;
    }
}
