<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
class Absence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // ✅ renamed for consistency with naming standards
    #[ORM\Column(name: "nbre_absences")]
    private ?int $nbreAbsences = null;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'absences')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(targetEntity: Matiere::class, inversedBy: 'absences')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Matiere $matiere = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbreAbsences(): ?int
    {
        return $this->nbreAbsences;
    }

    public function setNbreAbsences(int $nbreAbsences): static
    {
        $this->nbreAbsences = $nbreAbsences;
        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): static
    {
        $this->etudiant = $etudiant;
        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;
        return $this;
    }
}
