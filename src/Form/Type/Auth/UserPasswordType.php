<?php

namespace App\Form\Type\Auth;

use App\Entity\Auth\UserPassword;
use App\Entity\UserAuth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add("email", TextType::class)
      ->add("password", PasswordType::class)
      ->add("create", SubmitType::class)
    ;
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' => UserPassword::class,]);
  }
}