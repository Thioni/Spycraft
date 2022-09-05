<?php

namespace App\Entity;

use App\Repository\TargetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TargetRepository::class)]
class Target
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(
      min: 2,
      max: 20,
      minMessage: 'Le prénom doit comporter au moins 2 caractères',
      maxMessage: 'Le prénom doit comporter au maximum 20 caractères'
    )]
    private ?string $first_name = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(
      min: 2,
      max: 20,
      minMessage: 'Le nom doit comporter au moins 2 caractères',
      maxMessage: 'Le nom doit comporter au maximum 20 caractères'
    )]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(
      min: 2,
      max: 40,
      minMessage: 'Le nom de code doit comporter au moins 2 caractères',
      maxMessage: 'Le nom de code doit comporter au maximum 20 caractères'
    )]
    private ?string $codename = null;

    #[ORM\Column(length: 30)]
    private ?string $nationality = null;

    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'target')]
    private Collection $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCodename(): ?string
    {
        return $this->codename;
    }

    public function setCodename(string $codename): self
    {
        $this->codename = $codename;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->addTarget($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeTarget($this);
        }

        return $this;
    }

    public function __toString(): string
    {
      return $this->getNationality()." - ".$this->getFirstName()." ".$this->getLastName();
    }
}
