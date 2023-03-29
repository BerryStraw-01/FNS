<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TextType extends \Symfony\Component\Form\Extension\Core\Type\TextType {
  public function configureOptions(OptionsResolver $resolver) {
    parent::configureOptions($resolver);
    $resolver->setDefaults(["constraints" => [new Length(max: 225)]]);
  }

}