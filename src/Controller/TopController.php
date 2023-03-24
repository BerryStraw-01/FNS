<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopController extends AbstractController {
  #[Route('/', name: 'app_top')]
  public function index(Request $request): Response {
    return $this->render("index.html.twig",[]);
  }
}
