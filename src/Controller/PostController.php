<?php

namespace App\Controller;

use App\Entity\Post\Post;
use App\Form\Type\TextType;
use App\Repository\Post\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route("/user")]
class PostController extends AbstractController {
  public function __construct(
    private readonly PostRepository $postRepository
  ) {
  }

  #[Route("/", name: "app_post")]
  public function index() {
    $post = new Post();
    $form = $this->createFormBuilder($post)
      ->add("title", TextType::class, ["constraints" => [new Length(max: 30)]])
      ->add("description", TextareaType::class, ["constraints" => [new NotBlank()]])
      ->add("post", SubmitType::class)
      ->getForm()
    ;

    if ($form->isSubmitted() && $form->isValid()) {
      $post->setUser($this->getUser());
      $this->postRepository->save($post);

      return $this->redirectToRoute("app_top");
    }

    return $this->render("post/index.html.twig", [
      "form" => $form->createView()
    ]);
  }
}
