<?php

namespace App\Form\Type\Auth;

use App\FormEntity\Auth\FormUuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormUuidType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->addViewTransformer(new UuidToStringTransformer())
    ;
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([ 'attr' => ['style' => 'display:none;']]);
  }
}