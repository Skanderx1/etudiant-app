<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * Used for file upload only (not persisted)
     */
    private ?UploadedFile $photoFile = null;

    /**
     * @var Collection<int, EtudiantMatiere>
     */
    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: EtudiantMatiere::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $etudiantMatieres;

    /**
     * @var Collection<int, Absence>
     */
    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Absence::class, cascade: ['remove'])]
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

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;
        return $this;
    }

    public function setPhotoFile(?UploadedFile $file): self
    {
        $this->photoFile = $file;
        return $this;
    }

    public function getPhotoFile(): ?UploadedFile
    {
        return $this->photoFile;
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
            $etudiantMatiere->setEtudiant($this);
        }
        return $this;
    }

    public function removeEtudiantMatiere(EtudiantMatiere $etudiantMatiere): static
    {
        if ($this->etudiantMatieres->removeElement($etudiantMatiere)) {
            if ($etudiantMatiere->getEtudiant() === $this) {
                $etudiantMatiere->setEtudiant(null);
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

    public function addAbsence(Absence $absence): static
    {
        if (!$this->absences->contains($absence)) {
            $this->absences->add($absence);
            $absence->setEtudiant($this);
        }
        return $this;
    }

    public function removeAbsence(Absence $absence): static
    {
        if ($this->absences->removeElement($absence)) {
            if ($absence->getEtudiant() === $this) {
                $absence->setEtudiant(null);
            }
        }
        return $this;
    }

    public function getTotalAbsences(): int
    {
        return $this->absences->count();
    }
}
