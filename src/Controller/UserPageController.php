<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/user")]
class UserPageController extends AbstractController {
  public function __construct(
    private readonly UserRepository $userRepository
  ) {
  }

//  #[Route("/", name: "app_user")]
//  public function index() {
//
//  }

  #[Route('/{username}', name: 'app_user_page')]
  public function page(string $username): Response {
    $user = $this->userRepository->findOneBy(["username" => $username]);

    if ($user == null) {
      throw    $this->createNotFoundException("ユーザー".$username."が見つかりませんでした。");
    }

    return $this->render("user/page.html.twig", [
      "user" => $user
    ]);
  }
}
