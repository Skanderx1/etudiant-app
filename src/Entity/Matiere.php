<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $maxAbscences = null;

    /**
     * @var Collection<int, EtudiantMatiere>
     */
    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: EtudiantMatiere::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $etudiantMatieres;

    /**
     * @var Collection<int, Absence>
     */
    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Absence::class, cascade: ['remove'])]
    private Collection $absences;

    public function __construct()
    {
        $this->etudiantMatieres = new ArrayCollection();
        $this->absences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getMaxAbscences(): ?int
    {
        return $this->maxAbscences;
    }

    public function setMaxAbscences(int $maxAbscences): static
    {
        $this->maxAbscences = $maxAbscences;
        return $this;
    }

    /**
     * @return Collection<int, EtudiantMatiere>
     */
    public function getEtudiantMatieres(): Collection
    {
        return $this->etudiantMatieres;
    }

    public function addEtudiantMatiere(EtudiantMatiere $etudiantMatiere): static
    {
        if (!$this->etudiantMatieres->contains($etudiantMatiere)) {
            $this->etudiantMatieres->add($etudiantMatiere);
            $etudiantMatiere->setMatiere($this);
        }
        return $this;
    }

    public function removeEtudiantMatiere(EtudiantMatiere $etudiantMatiere): static
    {
        if ($this->etudiantMatieres->removeElement($etudiantMatiere)) {
            if ($etudiantMatiere->getMatiere() === $this) {
                $etudiantMatiere->setMatiere(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Absence>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }
}
