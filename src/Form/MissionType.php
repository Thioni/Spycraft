<?php
namespace App\Form;

use App\Entity\Mission;
use App\Entity\Speciality;
use App\Entity\Agent;
use App\Entity\Target;
use App\Entity\Contact;
use App\Entity\Hideout;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder 
    ->add("title", TextType::class, ["label" => "Titre"])
    ->add("description", TextareaType::class, [
        "label" => "Déscription",
        "attr" => ["style" => "height: 150px"]
    ])
    ->add("mission_type", TextType::class, ["label" => "Type de mission"])
    ->add("mission_status", ChoiceType::class, [
      "label" => "Status",
      "choices" => [
        "En préparation" => "En préparation",
        "En cours" => "En cours",
        "Terminée" => "Terminée",
        "Abandonnée" => "Abandonée",
        ]
    ])
    ->add("speciality", EntityType::class, [
        "class" => Speciality::class,
        "label" => "Spécialité requise",
    ])
    ->add("date_start", DateType::class, [
        "label" => "Date de début",
        "years" => range(date('Y'), date('Y') +50),
        'data' => new \DateTime()
    ])
    ->add("date_end", DateType::class, [
        "label" => "Date de fin",
        "years" => range(date('Y'), date('Y') +50),
        'data' => new \DateTime()
    ])
    ->add("codename", TextType::class, ["label" => "Nom de code"])
    ->add("country", ChoiceType::class, [
      "label" => "Pays",
      "choices" => [
        "Allemagne" => "Allemagne",
        "Belgique" => "Belgique",
        "Chine" => "Chine",
        "États-Unis" => "États-Unis",
        "France" => "France",
        "Japon" => "Japon",
        "Mexique" => "Mexique",
        "Royaume-Uni" => "Royaume-Uni",
        "Russie" => "Russie",
        "Singapour" => "Singapour",
        "Thailande" => "Thailande",
      ]
    ])
    ->add("agent", EntityType::class, [
      "class" => Agent::class,
      "label" => "Agent(s)",
      "multiple" => true,
      "expanded" => true,

  ])
    ->add("target", EntityType::class, [
      "class" => Target::class,
      "label" => "Cible(s)",
      'attr' => ['required' => true],
      "multiple" => true,
      "expanded" => true
  ])
    ->add("contact", EntityType::class, [
      "class" => Contact::class,
      "label" => "Contact(s)",
      "multiple" => true,
      "expanded" => true
  ])
    ->add("hideout", EntityType::class, [
      "class" => Hideout::class,
      "label" => "Planque(s)",
      "multiple" => true,
      "expanded" => true,
    ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      "data_class" => Mission::class
    ]);
  }

}