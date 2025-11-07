<?php

namespace App\Entity;

use App\Repository\EtudiantMatiereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantMatiereRepository::class)]
#[ORM\Table(name: 'etudiant_matiere')]
class EtudiantMatiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $absences = 0;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'etudiantMatieres')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(targetEntity: Matiere::class, inversedBy: 'etudiantMatieres')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Matiere $matiere = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbsences(): ?int
    {
        return $this->absences;
    }

    public function setAbsences(?int $absences): static
    {
        $this->absences = $absences;
        return $this;
    }

    public function incrementAbsences(int $n = 1): static
    {
        $this->absences = ($this->absences ?? 0) + $n;
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
