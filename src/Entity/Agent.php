<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
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

    #[ORM\Column]
    #[Assert\GreaterThan(
      value: 100,
      message: "Le code d'identification doit être supérieur à 100"
    )]
    #[Assert\LessThan(
      value: 1000,
      message: "Le code d'identification doit être inférieur à 1000"
    )]
    private ?int $code_agent = null;

    #[ORM\Column(length: 40)]
    private ?string $nationality = null;

    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'agent')]
    private Collection $missions;

    #[ORM\ManyToMany(targetEntity: Speciality::class, inversedBy: 'agents')]
    #[Assert\Count(
      min: 1,
      minMessage: 'Vous devez selectionner au moins une spécialité',
    )]
    private Collection $skill;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->skill = new ArrayCollection();
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

    public function getCodeAgent(): ?int
    {
        return $this->code_agent;
    }

    public function setCodeAgent(int $code_agent): self
    {
        $this->code_agent = $code_agent;

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
            $mission->addAgent($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Speciality>
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Speciality $skill): self
    {
        if (!$this->skill->contains($skill)) {
            $this->skill->add($skill);
        }

        return $this;
    }

    public function removeSkill(Speciality $skill): self
    {
        $this->skill->removeElement($skill);

        return $this;
    }
    
    public function __toString(): string
    {
      return $this->getNationality()." - ".$this->getFirstName()." ".$this->getLastName();
    }
}
