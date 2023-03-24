<?php

namespace App\Controller;

use App\Entity\FormPhase;
use App\Form\Type\FormPhaseType;
use App\Repository\UserAuthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends AbstractController {
  #[Route('/create-user', name: 'app_create_user')]
  public function index(Request $request, UserAuthRepository $userAuthRepository): Response {
    $phaseForm = $this->createForm(FormPhaseType::class);
    $phaseForm->handleRequest($request);
    /** @var FormPhase $phaseData */
    $phaseData = $phaseForm->getData();

    switch ($phaseData->getPhase()) {
      case 1:
      break;
      case 2:
      break;
    }

    return $this->render('create-user/index.html.twig', [
      "phaseForm" => $phaseForm,
    ]);
  }
}
