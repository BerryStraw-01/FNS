<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserProfileType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add("email", TextType::class, ["constraints" => [new Length(max: 118)]])
      ->add("username", TextType::class)
      ->add("password", PasswordType::class)
    ;
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' => User::class,]);
  }
}