<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\User;
use App\Form\Type\TextType;
use App\Repository\CommunityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route("/community")]
class CommunityController extends AbstractController {

  public function __construct(
    private readonly CommunityRepository $communityRepository,
  ) {
  }

  #[Route('/', name: 'app_community')]
  public function index(AuthenticationUtils $authenticationUtils): Response {
    $communities = $this->communityRepository->findByUpdated(20);

    return $this->render('community/index.html.twig', [
      'communities' => $communities,
    ]);
  }

  #[Route('/create', name: 'app_community_create')]
  public function create(Request $request): Response {
    $community = new Community();
    $form = $this->createFormBuilder($community)
      ->add("name", TextType::class)
      ->add("description", TextareaType::class)
      ->add("create", SubmitType::class)
      ->getForm()
    ;
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->communityRepository->findOneBy(["name" => $community->getName()]) != null)
        return $this->render("community/create/create.html.twig", [
          "form"   => $form->createView(),
          "errors" => ["'".$community->getName()."'はすでに使用されています"]
        ]);
      /** @var User $user */
      $user = $this->getUser();

      $community->setOwner($user);
      $community->addUser($user);

      $this->communityRepository->save($community);
    }

    return $this->render('community/create/create.html.twig', [
      'form' => $form->createView(),
    ]);
  }
}