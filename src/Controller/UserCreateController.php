<?php

namespace App\Controller;

use App\Entity\UserAuth;
use App\Form\Type\Auth\EmailCodeType;
use App\Form\Type\Auth\FormUuidType;
use App\Form\Type\Auth\UserPasswordType;
use App\FormEntity\Auth\FormUuid;
use App\Repository\UserAuthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends AbstractController {
  private UserAuthRepository $userAuthRepository;
  private UserPasswordHasherInterface $userPasswordHasher;

  /**
   * @param UserAuthRepository $userAuthRepository
   * @param UserPasswordHasherInterface $userPasswordHasher
   */
  public function __construct(UserAuthRepository $userAuthRepository, UserPasswordHasherInterface $userPasswordHasher) {
    $this->userAuthRepository = $userAuthRepository;
  }

  #[Route('/create-user', name: 'app_create_user')]
  public function index(Request $request): Response {
    $uuidForm = $this->createForm(FormUuidType::class);
    $uuidForm->handleRequest($request);
    /** @var FormUuid $formUuid */
    $formUuid = $uuidForm->getData();

    if (!$uuidForm->isSubmitted() || !$uuidForm->isValid()) {
      return $this->newForm($request, $uuidForm);
    }

    $userAuth = $this->userAuthRepository->findOneBy(["uuid" => $formUuid->getUuid()]);
    if ($userAuth->getEmail() == null || $userAuth->getPassword() == null) {
      return $this->userPasswordForm($request, $uuidForm);
    }

    return $this->emailCodeForm($request, $uuidForm);
  }

  private function emailCodeForm(Request $request, FormInterface $uuidForm) {
    $emailCodeForm = $this->createForm(EmailCodeType::class);
    $emailCodeForm->handleRequest($request);

    return $this->render("create-user/index.html.twig", [
      "phaseForm" => $uuidForm,
      "form"      => $emailCodeForm
    ]);
  }

  private function userPasswordForm(Request $request, UserAuth $userAuth, FormInterface $uuidForm): Response {
    $userPasswordForm = $this->createForm(UserPasswordType::class);
    $userPasswordForm->handleRequest($request);

    if ($userPasswordForm->isSubmitted() && $userPasswordForm->isValid()) {
      $userAuth->setPassword();
      return $this->emailCodeForm($request, $uuidForm);
    }

    return $this->render('create-user/index.html.twig', [
      "phaseForm" => $uuidForm->createView(),
      "form"      => $userPasswordForm
    ]);
  }

  private function newForm(Request $request, FormInterface $uuidForm): Response {
    $userAuth = UserAuth::create();
    $this->userAuthRepository->save($userAuth);
    $uuidForm->setData(new FormUuid($userAuth->getUuid()));

    return $this->userPasswordForm($request, $userAuth, $uuidForm);
  }
}
