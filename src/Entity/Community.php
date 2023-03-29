<?php

namespace App\Entity;

use App\Repository\CommunityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: CommunityRepository::class)]
class Community {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;
  #[ORM\Column(nullable: false)]
  private \DateTimeImmutable $updateAt;
  #[ORM\Column(length: 20, nullable: false)]
  #[Length(max: 20)]
  private ?string $name = null;
  #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'communities')]
  private Collection $users;
  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'communities')]
  private User $owner;

  #[ORM\Column(length: 255)]
  #[Length(max: 255)]
  private ?string $description = null;

  public function __construct() {
    $this->users = new ArrayCollection();
  }

  /**
   * @return \DateTimeImmutable
   */
  public function getUpdateAt(): \DateTimeImmutable {
    return $this->updateAt;
  }

  /**
   * @param \DateTimeImmutable $updateAt
   */
  public function setUpdateAt(\DateTimeImmutable $updateAt): void {
    $this->updateAt = $updateAt;
  }

  /**
   * @return User
   */
  public function getOwner(): User {
    return $this->owner;
  }

  /**
   * @param User $owner
   */
  public function setOwner(User $owner): void {
    $this->owner = $owner;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, User>
   */
  public function getUsers(): Collection {
    return $this->users;
  }

  public function addUser(User $user): self {
    if (!$this->users->contains($user)) {
      $this->users->add($user);
    }

    return $this;
  }

  public function removeUser(User $user): self {
    $this->users->removeElement($user);

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(string $description): self {
    $this->description = $description;

    return $this;
  }
}
