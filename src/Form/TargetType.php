<?php
namespace App\Form;

use App\Entity\Target;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder 
        ->add("first_name", TextType::class, ["label" => "Prénom"])
        ->add("last_name", TextType::class, ["label" => "Nom"])
        ->add("birthdate", DateType::class, [
            "label" => "Date de naissance",
            "years" => range(date('Y') -100, date('Y'))
        ])
        ->add("codename", TextType::class, ["label" => "Nom de code"])
        ->add("nationality", ChoiceType::class, [
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
        ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      "data_class" => Target::class
    ]);
  }

}