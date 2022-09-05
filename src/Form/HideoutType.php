<?php
namespace App\Form;

use App\Entity\Hideout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HideoutType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder 
        ->add("code_hideout", IntegerType::class, ["label" => "Code de la planque"])
        ->add("adress", TextType::class, ["label" => "Adresse"])
        ->add("type", TextType::class, ["label" => "Type"])
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
        ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      "data_class" => Hideout::class
    ]);
  }

}