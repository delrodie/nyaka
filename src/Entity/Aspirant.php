<?php

namespace App\Entity;

use App\Repository\AspirantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AspirantRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Aspirant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('participation')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $urgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $contactUrgence = null;

    #[ORM\Column(nullable: true)]
    #[Groups('participation')]
    private ?int $montant = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $teeshirt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('participation')]
    private ?string $taille = null;

    #[ORM\Column(nullable: true)]
    #[Groups('participation')]
    private ?int $montantTeeshirt = null;

    #[ORM\ManyToOne(inversedBy: 'aspirants')]
    #[Groups('participation')]
    private ?Grade $grade = null;

    #[ORM\ManyToOne(inversedBy: 'aspirants')]
    #[Groups('participation')]
    private ?Section $section = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('participation')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('participation')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

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

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUrgence(): ?string
    {
        return $this->urgence;
    }

    public function setUrgence(?string $urgence): static
    {
        $this->urgence = $urgence;

        return $this;
    }

    public function getContactUrgence(): ?string
    {
        return $this->contactUrgence;
    }

    public function setContactUrgence(?string $contactUrgence): static
    {
        $this->contactUrgence = $contactUrgence;

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

    public function getTeeshirt(): ?string
    {
        return $this->teeshirt;
    }

    public function setTeeshirt(?string $teeshirt): static
    {
        $this->teeshirt = $teeshirt;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getMontantTeeshirt(): ?int
    {
        return $this->montantTeeshirt;
    }

    public function setMontantTeeshirt(?int $montantTeeshirt): static
    {
        $this->montantTeeshirt = $montantTeeshirt;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): \DateTime
    {
        return $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdateAtValue(): \DateTime
    {
        return $this->updatedAt = new \DateTime();
    }
}
