<?php

namespace App\Controller;

use App\Entity\UserAuth;
use App\Form\Type\UserPasswordType;
use App\Repository\UserAuthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends AbstractController {
  #[Route('/create-user', name: 'app_create_user')]
  public function index(Request $request, UserAuthRepository $userAuthRepository): Response {
    $formData = new UserAuth();

    $form = $this->createForm(UserPasswordType::class, $formData);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $userAuth = $userAuthRepository->findOneBy(["uuid" => $formData->getUuid()]);
      $userAuth->setEmail($formData->getEmail());
      $userAuth->setPassword($formData->getPassword());
      $userAuthRepository->save($userAuth);

    }


    return $this->render('create-user/index.html.twig', ['controller_name' => 'CreateUserController',]);
  }
}
