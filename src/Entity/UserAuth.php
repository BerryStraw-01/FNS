<?php

namespace App\Entity;

use App\Repository\UserAuthRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints\Uuid;

#[ORM\Entity(repositoryClass: UserAuthRepository::class)]
class UserAuth {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;
  #[ORM\Column]
  #[Unique]
  private Uuid $uuid;
  #[ORM\Column]
  private DateTime $createAt;
  #[ORM\Column]
  private ?string $password = null;
  #[ORM\Column]
  #[Email]
  private ?string $email = null;
  /**
   * @var string|null hashed verify token
   */
  #[ORM\Column(length: 8)]
  private ?string $token = null;

  protected function __construct() {
  }

  public static function create(): UserAuth {
    $userAuth = new UserAuth();
    $userAuth->createAt = new DateTime("now");
    $userAuth->uuid = new Uuid();
    return $userAuth;
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
   * @return Uuid
   */
  public function getUuid(): Uuid {
    return $this->uuid;
  }

  /**
   * @return string|null
   */
  public function getToken(): ?string {
    return $this->token;
  }

  /**
   * @param string|null $token
   */
  public function setToken(?string $token): void {
    $this->token = $token;
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

  public function getCreateAt(): DateTime {
    return $this->createAt;
  }
}
