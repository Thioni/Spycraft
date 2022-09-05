<?php

namespace App\Entity;

use App\Repository\SpecialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecialityRepository::class)]
class Speciality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\Regex(
      pattern: "/[a-zA-Z]/",
      match: true,
      message: 'Caractère non autorisé: vous ne pouvez utiliser que des lettres',
    )]
    #[Assert\Length(
      min: 3,
      max: 20,
      minMessage: 'Une spécialité doit comporter au moins 3 caractères',
      maxMessage: 'Une spécialité doit comporter au maximum 20 caractères'
    )]
    private ?string $skill = null;

    #[ORM\ManyToMany(targetEntity: Agent::class, mappedBy: 'skill')]
    private Collection $agents;

    #[ORM\OneToMany(mappedBy: 'speciality', targetEntity: Mission::class)]
    private Collection $missions;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkill(): ?string
    {
        return $this->skill;
    }

    public function setSkill(string $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->addSkill($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->removeElement($agent)) {
            $agent->removeSkill($this);
        }

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
            $mission->setSpeciality($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getSpeciality() === $this) {
                $mission->setSpeciality(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
      return $this->getSkill();
    }
}
