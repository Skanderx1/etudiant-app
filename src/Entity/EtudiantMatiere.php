<?php

namespace App\Entity;

use App\Repository\EtudiantMatiereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantMatiereRepository::class)]
class EtudiantMatiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $absences = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbsences(): ?int
    {
        return $this->absences;
    }

    public function setAbsences(int $absences): static
    {
        $this->absences = $absences;

        return $this;
    }
}
