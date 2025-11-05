<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $math = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $algorithme = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $english = null;

    #[ORM\Column(length: 200)]
    private ?string $arabic = null;

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

    public function getMath(): ?string
    {
        return $this->math;
    }

    public function setMath(?string $math): static
    {
        $this->math = $math;

        return $this;
    }

    public function getAlgorithme(): ?string
    {
        return $this->algorithme;
    }

    public function setAlgorithme(?string $algorithme): static
    {
        $this->algorithme = $algorithme;

        return $this;
    }

    public function getEnglish(): ?string
    {
        return $this->english;
    }

    public function setEnglish(?string $english): static
    {
        $this->english = $english;

        return $this;
    }

    public function getArabic(): ?string
    {
        return $this->arabic;
    }

    public function setArabic(string $arabic): static
    {
        $this->arabic = $arabic;

        return $this;
    }
}
