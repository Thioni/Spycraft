<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(
      min: 3,
      max: 30,
      minMessage: 'Le titre doit comporter au moins 3 caractères',
      maxMessage: 'Le titre doit comporter au maximum 30 caractères'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
      min: 10,
      max: 600,
      minMessage: 'La déscription doit comporter au moins 10 caractères',
      maxMessage: 'La déscription doit comporter au maximum 600 caractères'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $mission_type = null;

    #[ORM\Column(length: 20)]
    private ?string $mission_status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column(length: 20)]
    private ?string $codename = null;

    #[ORM\Column(length: 30)]
    private ?string $country = null;

    #[ORM\ManyToMany(targetEntity: Agent::class, inversedBy: 'missions')]
    #[Assert\Count(
      min: 1,
      minMessage: 'Vous devez selectionner au moins un agent',
    )]
    private Collection $agent;

    #[ORM\ManyToMany(targetEntity: Target::class, inversedBy: 'missions')]
    #[Assert\Count(
      min: 1,
      minMessage: 'Vous devez selectionner au moins une cible',
    )]
    private Collection $target;

    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'missions')]
    #[Assert\Count(
      min: 1,
      minMessage: 'Vous devez selectionner au moins un contact',
    )]
    private Collection $contact;

    #[ORM\ManyToMany(targetEntity: Hideout::class, inversedBy: 'missions')]
    private Collection $hideout;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Speciality $speciality = null;

    public function __construct()
    {
        $this->agent = new ArrayCollection();
        $this->target = new ArrayCollection();
        $this->contact = new ArrayCollection();
        $this->hideout = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMissionType(): ?string
    {
        return $this->mission_type;
    }

    public function setMissionType(string $mission_type): self
    {
        $this->mission_type = $mission_type;

        return $this;
    }

    public function getMissionStatus(): ?string
    {
        return $this->mission_status;
    }

    public function setMissionStatus(string $mission_status): self
    {
        $this->mission_status = $mission_status;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

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
     * @return Collection<int, Agent>
     */
    public function getAgent(): Collection
    {
        return $this->agent;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agent->contains($agent)) {
            $this->agent->add($agent);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        $this->agent->removeElement($agent);

        return $this;
    }

    /**
     * @return Collection<int, Target>
     */
    public function getTarget(): Collection
    {
        return $this->target;
    }

    public function addTarget(Target $target): self
    {
        if (!$this->target->contains($target)) {
            $this->target->add($target);
        }

        return $this;
    }

    public function removeTarget(Target $target): self
    {
        $this->target->removeElement($target);

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contact->contains($contact)) {
            $this->contact->add($contact);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        $this->contact->removeElement($contact);

        return $this;
    }

    /**
     * @return Collection<int, Hideout>
     */
    public function getHideout(): Collection
    {
        return $this->hideout;
    }

    public function addHideout(Hideout $hideout): self
    {
        if (!$this->hideout->contains($hideout)) {
            $this->hideout->add($hideout);
        }

        return $this;
    }

    public function removeHideout(Hideout $hideout): self
    {
        $this->hideout->removeElement($hideout);

        return $this;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }
    
    public function targetsNationalityCheck() {
      
      $dataAgents = $this->agent;
      $dataTargets = $this->target;

      foreach ($dataAgents as $dataAgent) {
        foreach ($dataTargets as $dataTarget) {
          if ($dataAgent->getNationality() == $dataTarget->getNationality()) {
              return false;
          }
        }
      }
      return true;
    }

    public function contactsNationalityCheck() {

      $dataContacts = $this->contact;
      $dataCountry = $this->country;

      foreach ($dataContacts as $dataContact) {
       if ($dataContact->getNationality() == $dataCountry) {
           return true;
       }
      }
      return false;
    }

    public function hideoutsCountryCheck() {

      $dataHideouts = $this->hideout;
      $dataCountry = $this->country;

      if ($dataHideouts->isEmpty()) {
        return true;
      } 

      foreach ($dataHideouts as $dataHideout) {
        if ($dataHideout->getCountry() != $dataCountry) {
          return false;
        }
      } 
      return true;
    }

   public function specialityCheck() {

      $dataSpeciality = $this->speciality;
      $dataAgents = $this->agent;
      $skillCounter = 0;
 
      foreach ($dataAgents as $dataAgent) {
         if ($dataAgent->getSkill()->contains($dataSpeciality) == $dataSpeciality) {
            $skillCounter += 1;
         }
      }  

      if ($skillCounter == 0) {
        return false;
      } else {
        return true;
      }
    }

}
