<?php

namespace App\Entity;

use App\Repository\HideoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HideoutRepository::class)]
class Hideout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $code_hideout = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
      min: 10,
      max: 90,
      minMessage: 'Le nom doit comporter au moins 10 caractères',
      maxMessage: 'Le nom doit comporter au maximum 50 caractères'
    )]
    private ?string $adress = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 30)]
    private ?string $country = null;

    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'hideout')]
    private Collection $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeHideout(): ?int
    {
        return $this->code_hideout;
    }

    public function setCodeHideout(int $code_hideout): self
    {
        $this->code_hideout = $code_hideout;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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
            $mission->addHideout($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeHideout($this);
        }

        return $this;
    }

    public function __toString(): string
    {
      return $this->getCountry()." - ".$this->getType(). " - ".$this->getAdress();
    }
}
