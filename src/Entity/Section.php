<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
class Section
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
    private ?string $paroisse = null;

    #[ORM\ManyToOne(inversedBy: 'sections')]
    #[Groups('participation')]
    private ?Doyenne $doyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $slug = null;

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

    public function getParoisse(): ?string
    {
        return $this->paroisse;
    }

    public function setParoisse(?string $paroisse): static
    {
        $this->paroisse = $paroisse;

        return $this;
    }

    public function getDoyenne(): ?Doyenne
    {
        return $this->doyenne;
    }

    public function setDoyenne(?Doyenne $doyenne): static
    {
        $this->doyenne = $doyenne;

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
}
