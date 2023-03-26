<?php

namespace App\Entity;

use App\Repository\UserAuthRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: UserAuthRepository::class)]
class UserAuth implements PasswordAuthenticatedUserInterface {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;
  #[ORM\Column(length: 30, nullable: false)]
  #[Unique]
  private string $sessionId;
  #[ORM\Column(nullable: false)]
  private bool $expected = false;
  #[ORM\Column(nullable: false)]
  private DateTimeImmutable $createAt;
  #[ORM\Column(nullable: true)]
  private ?string $password = null;
  #[ORM\Column(nullable: true)]
  #[Email]
  private ?string $email = null;
  /**
   * @var string|null hashed verify token
   */
  #[ORM\Column(length: 9, nullable: true)]
  private ?string $code = null;

  protected function __construct() {
  }

  public static function create(string $sessionId): UserAuth {
    $userAuth = new UserAuth();
    $userAuth->createAt = new DateTimeImmutable("now");
    $userAuth->sessionId = $sessionId;
    return $userAuth;
  }

  /**
   * @return bool
   */
  public function isExpected(): bool {
    return $this->expected;
  }

  /**
   * @param bool $expected
   */
  public function setExpected(bool $expected): void {
    $this->expected = $expected;
  }

  /**
   * @return string|null
   */
  public function getPassword(): ?string {
    return $this->password;
  }

  /**
   * @param string|null $password
   */
  public function setPassword(?string $password): void {
    $this->password = $password;
  }

  /**
   * @return string
   */
  public function getSessionId(): string {
    return $this->sessionId;
  }

  /**
   * @return string|null
   */
  public function getCode(): ?string {
    return $this->code;
  }

  /**
   * @param string|null $code
   */
  public function setCode(?string $code): void {
    $this->code = $code;
  }

  /**
   * @return string|null
   */
  public function getEmail(): ?string {
    return $this->email;
  }

  /**
   * @param string|null $email
   */
  public function setEmail(?string $email): void {
    $this->email = $email;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getCreateAt(): DateTimeImmutable {
    return $this->createAt;
  }
}
