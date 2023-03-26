<?php

namespace App\Controller;

use App\Entity\UserAuth;
use App\Form\Type\Auth\EmailCodeType;
use App\FormEntity\Auth\EmailCode;
use App\FormEntity\Auth\EmailPassword;
use App\Repository\UserAuthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserCreateController extends AbstractController {
  private UserAuthRepository $userAuthRepository;
  private MailerInterface $mailer;
  private UserPasswordHasherInterface $userPasswordHasher;
  private RequestStack $requestStack;

  /**
   * @param UserAuthRepository $userAuthRepository
   * @param UserPasswordHasherInterface $userPasswordHasher
   * @param MailerInterface $mailer
   * @param RequestStack $requestStack
   */
  public function __construct(UserAuthRepository $userAuthRepository, UserPasswordHasherInterface $userPasswordHasher,
                              MailerInterface    $mailer, RequestStack $requestStack) {
    $this->userAuthRepository = $userAuthRepository;
    $this->userPasswordHasher = $userPasswordHasher;
    $this->mailer = $mailer;
    $this->requestStack = $requestStack;
  }

  #[Route('/register', name: 'app_register')]
  public function index(Request $request): Response {
    $session = $this->requestStack->getSession();
    dump("index", $session->getId());
    if (!$session->isStarted()) {
      $session->start();
      return $this->newForm($request, $session);
    }


    $userAuth = $this->userAuthRepository->findOneBy(["sessionId" => $session->getId(), "expected" => false]);

    if ($userAuth == null) {
      return $this->newForm($request, $session);
    }

    if ((new \DateTimeImmutable())->sub(new \DateInterval("PT1H")) > $userAuth->getCreateAt()) {
      $userAuth->setExpected(true);
      $this->userAuthRepository->save($userAuth);
      return $this->newForm($request, $session);
    }

    if ($userAuth->getEmail() == null || $userAuth->getPassword() == null) {
      return $this->userPasswordForm($request, $userAuth, $session);
    }

    return $this->emailCodeForm($request, $userAuth, $session);
  }

  private function newForm(Request $request, Session $session): Response {
    dump("new form");
    $userAuth = UserAuth::create($session->getId());
    $this->userAuthRepository->save($userAuth);

    return $this->userPasswordForm($request, $userAuth, $session);
  }

  private function userPasswordForm(Request $request, UserAuth $userAuth, Session $session, array|null $errors = null): Response {
    dump("user password");
    $emailPassword = new EmailPassword();
    $userPasswordForm = $this->createFormBuilder($emailPassword)
      ->add("email", EmailType::class)
      ->add("password", PasswordType::class)
      ->add("create", SubmitType::class)
      ->getForm()
    ;
    $userPasswordForm->handleRequest($request);

    if ($errors == null && $userPasswordForm->isSubmitted() && $userPasswordForm->isValid()) {
      $userAuth->setPassword($this->userPasswordHasher->hashPassword($userAuth, $emailPassword->getPassword()));
      $userAuth->setEmail($emailPassword->getEmail());
      $this->userAuthRepository->save($userAuth);
      return $this->emailCodeForm($request, $userAuth, $session);
    }

    return $this->render('register/index.html.twig', [
      "form"   => $userPasswordForm->createView(),
      "errors" => $errors
    ]);
  }

  private function emailCodeForm(Request $request, UserAuth $userAuth, Session $session): Response {
    dump("email code");
    $emailCodeForm = $this->createForm(EmailCodeType::class);
    $emailCodeForm->handleRequest($request);

    if ($emailCodeForm->isSubmitted() && $emailCodeForm->isValid()) {
      /** @var EmailCode $emailCode */
      $emailCode = $emailCodeForm->getData();

      if ($emailCode->getCode() == $userAuth->getCode()) {
        $userAuth->setExpected(true);
        $this->userAuthRepository->save($userAuth);

        return $this->redirectToRoute("app_top");
      }
      return $this->render("register/index.html.twig", [
        "form"   => $emailCodeForm->createView(),
        "errors" => ["認証コードが正しくありません。"]
      ]);
    }

    try {
      $userAuth->setCode(bin2hex(random_bytes(8 / 2)));
    } catch (\Exception $e) {
      error_log($e);
      return $this->userPasswordForm($request, $userAuth, $uuidForm, ["認証コードの生成に失敗しました。しばらくしてからもう一度お試しください。"]);
    }
    dump($userAuth);
    $this->userAuthRepository->save($userAuth);

    $email = (new Email())
      ->from('wordpress@for-ns.littlestar.jp')
      ->to($userAuth->getEmail())
      //->cc('cc@example.com')
      //->bcc('bcc@example.com')
      //->replyTo('fabien@example.com')
      //->priority(Email::PRIORITY_HIGH)
      ->subject('Time for Symfony Mailer!')
      ->text('Sending emails is fun again!')
      ->html('<p>See Twig integration for better HTML integration!' . $userAuth->getCode() . '</p>')
    ;

    try {
      $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {
      error_log($e);
      return $this->userPasswordForm($request, $userAuth, $session, ["Emailを送信できませんでした。Emailアドレスを確認の上、もう一度送信してください。"]);
    }
    return $this->render("register/index.html.twig", [
      "form" => $emailCodeForm->createView(),
    ]);
  }
}
