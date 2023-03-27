<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;
  #[ORM\Column(length: 180, unique: true)]
  private string $email;
  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column]
  private string $password;
  #[ORM\Column]
  private ?string $username = null;

  #[ORM\ManyToMany(targetEntity: Communitiy::class, mappedBy: 'users')]
  private Collection $communities;

  protected function __construct()
  {
      $this->communities = new ArrayCollection();
  }

  public static function create(string $email, string $password): User {
    $user = new User();
    $user->email = $email;
    $user->password = $password;
    return $user;
  }

  public static function fromUserAuth(UserAuth $userAuth): User {
    return self::create($userAuth->getEmail(), $userAuth->getPassword());
  }


  public function getId(): ?int {
    return $this->id;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string {
    return (string)$this->email;
  }

  public function getUsername(): string {
    return (string)$this->username;
  }

  public function setUsername(string $username) {
    $this->username = $username;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  /**
   * Returning a salt is only needed, if you are not using a modern
   * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
   *
   * @see UserInterface
   */
  public function getSalt(): ?string {
    return null;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials() {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * @return Collection<int, Communitiy>
   */
  public function getCommunities(): Collection
  {
      return $this->communities;
  }

  public function addCommunity(Communitiy $community): self
  {
      if (!$this->communities->contains($community)) {
          $this->communities->add($community);
          $community->addUser($this);
      }

      return $this;
  }

  public function removeCommunity(Communitiy $community): self
  {
      if ($this->communities->removeElement($community)) {
          $community->removeUser($this);
      }

      return $this;
  }
}
