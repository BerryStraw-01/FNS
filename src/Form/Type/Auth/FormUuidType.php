<?php

namespace App\Form\Type\Auth;

use App\Entity\Auth\FormPhase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormUuidType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add("uuid", HiddenType::class)
      ->add("phase", NumberType::class);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' => FormPhase::class,]);
  }
}