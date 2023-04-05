<?php

namespace App\Entity;

use App\Entity\Post\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;
  #[ORM\Column(length: 180, unique: true)]
  #[Length(max: 180)]
  private string $email;
  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column]
  private string $password;
  #[ORM\Column]
  #[Unique]
  private ?string $username = null;

  #[ORM\ManyToMany(targetEntity: Community::class, mappedBy: 'users')]
  private Collection $communities;

  #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
  private Collection $userPosts;

  protected function __construct() {
    $this->communities = new ArrayCollection();
    $this->userPosts = new ArrayCollection();
  }

  public static function fromUserAuth(UserAuth $userAuth): User {
    return self::create($userAuth->getEmail(), $userAuth->getPassword());
  }

  public static function create(string $email, string $password): User {
    $user = new User();
    $user->email = $email;
    $user->password = $password;
    return $user;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

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

  public function getId(): ?int {
    return $this->id;
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
   * @return Collection<int, Community>
   */
  public function getCommunities(): Collection {
    return $this->communities;
  }

  public function addCommunity(Community $community): self {
    if (!$this->communities->contains($community)) {
      $this->communities->add($community);
      $community->addUser($this);
    }

    return $this;
  }

  public function removeCommunity(Community $community): self {
    if ($this->communities->removeElement($community)) {
      $community->removeUser($this);
    }

    return $this;
  }

  /**
   * @return Collection<int, Post>
   */
  public function getUserPosts(): Collection {
    return $this->userPosts;
  }

  public function addUserPost(Post $userPost): self {
    if (!$this->userPosts->contains($userPost)) {
      $this->userPosts->add($userPost);
      $userPost->setUser($this);
    }

    return $this;
  }

  public function removeUserPost(Post $userPost): self {
    if ($this->userPosts->removeElement($userPost)) {
      // set the owning side to null (unless already changed)
      if ($userPost->getUser() === $this) {
        $userPost->setUser(null);
      }
    }

    return $this;
  }
}
