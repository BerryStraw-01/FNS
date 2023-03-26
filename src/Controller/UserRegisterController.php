<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserAuth;
use App\Form\Type\Auth\EmailCodeType;
use App\FormEntity\Auth\EmailCode;
use App\Repository\UserAuthRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRegisterController extends AbstractController {
  private UserAuthRepository $userAuthRepository;
  private UserRepository $userRepository;
  private MailerInterface $mailer;
  private UserPasswordHasherInterface $userPasswordHasher;
  private RequestStack $requestStack;
  private Security $security;
  private UserProviderInterface $userProvider;

  /**
   * @param UserAuthRepository $userAuthRepository
   * @param UserPasswordHasherInterface $userPasswordHasher
   * @param MailerInterface $mailer
   * @param RequestStack $requestStack
   * @param UserRepository $userRepository
   * @param Security $security
   * @param UserProviderInterface $userProvider
   */
  public function __construct(
    UserAuthRepository          $userAuthRepository,
    UserPasswordHasherInterface $userPasswordHasher,
    MailerInterface             $mailer,
    RequestStack                $requestStack,
    UserRepository              $userRepository,
    Security                    $security,
    UserProviderInterface       $userProvider
  ) {
    $this->userAuthRepository = $userAuthRepository;
    $this->userPasswordHasher = $userPasswordHasher;
    $this->mailer = $mailer;
    $this->requestStack = $requestStack;
    $this->userRepository = $userRepository;
    $this->security = $security;
    $this->userProvider = $userProvider;
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
      return $this->newForm($request, $session, ["登録開始から1時間以上経過しました。再度最初からやり直してください。"]);
    }

    if ($userAuth->getEmail() == null || $userAuth->getPassword() == null)
      return $this->userPasswordForm($request, $userAuth, $session);


    if (!$userAuth->isAuthed())
      return $this->emailCodeForm($request, $userAuth, $session);

    return $this->createUser($request, $userAuth);
  }

  private function newForm(Request $request, Session $session, array $errors = null): Response {
    dump("new form");
    $userAuth = UserAuth::create($session->getId());
    $this->userAuthRepository->save($userAuth);

    return $this->userPasswordForm($request, $userAuth, $session, $errors);
  }

  private function userPasswordForm(
    Request    $request,
    UserAuth   $userAuth,
    Session    $session,
    array|null $errors = null
  ): Response {
    dump("user password");
    $form = $this->createFormBuilder()
      ->add("email", EmailType::class)
      ->add("password", PasswordType::class)
      ->add("create", SubmitType::class)
      ->getForm()
    ;
    $form->handleRequest($request);
    $data = $form->getData();

    if ($errors == null && $form->isSubmitted() && $form->isValid()) {
      if ($this->userRepository->findOneBy(["email" => $data["email"]]) != null) {
        return $this->render('register/index.html.twig', [
          "form"   => $form->createView(),
          "errors" => array_merge($errors, ["Email: '".$data["email"]."'はすでに登録されています。"])
        ]);
      }

      $userAuth->setPassword($this->userPasswordHasher->hashPassword($userAuth, $data["password"]));
      $userAuth->setEmail($data["email"]);
      $this->userAuthRepository->save($userAuth);
      return $this->emailCodeForm($request, $userAuth, $session);
    }

    return $this->render('register/index.html.twig', [
      "form"   => $form->createView(),
      "errors" => $errors
    ]);
  }

  private function emailCodeForm(Request $request, UserAuth $userAuth, Session $session): Response {
    dump("email code");
    $form = $this->createForm(EmailCodeType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var EmailCode $emailCode */
      $emailCode = $form->getData();

      if ($emailCode->getCode() == $userAuth->getCode()) {
        $userAuth->setAuthed(true);
        $this->userAuthRepository->save($userAuth);

        return $this->createUser($request, $userAuth, $session);
      }
      return $this->render("register/index.html.twig", [
        "form"   => $form->createView(),
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
      ->html('<p>See Twig integration for better HTML integration!'.$userAuth->getCode().'</p>')
    ;

    try {
      $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {
      error_log($e);
      return $this->userPasswordForm($request, $userAuth, $session,
                                     ["Emailを送信できませんでした。Emailアドレスを確認の上、もう一度送信してください。"]);
    }
    return $this->render("register/index.html.twig", [
      "form" => $form->createView(),
    ]);
  }

  private function createUser(Request $request, UserAuth $userAuth): Response {
    $user = $this->userRepository->findOneBy(["email" => $userAuth->getEmail()]);
    if ($user == null) $user = User::create($userAuth->getEmail(), $userAuth->getPassword());

    $form = $this->createFormBuilder()
      ->add("username", TextType::class)
      ->add("register", SubmitType::class)
      ->getForm()
      ->handleRequest($request)
    ;
    $data = $form->getData();

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->userRepository->findOneByNotEmailAndUsername($user->getEmail(), $data["username"]) != null) {
        return $this->indexRender($form, [$data["username"]."はすでに使用されています。"]);
      }

      $user->setUsername($data["username"]);

      $this->userRepository->save($user);

      $this->userProvider->refreshUser($user);

      $this->security->login($user);
      return $this->redirectToRoute("app_top");
    }

    return $this->indexRender($form);
  }

  private function indexRender(FormInterface $form, array $errors = null) {
    return $this->render("register/index.html.twig", [
      "form"   => $form->createView(),
      "errors" => $errors
    ]);
  }
}
