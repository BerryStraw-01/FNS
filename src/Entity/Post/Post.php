<?php

namespace App\Entity\Post;

use App\Entity\User;
use App\Repository\Post\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $postAt = null;

  #[ORM\Column(length: 30)]
  private ?string $title = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $description = null;
  #[ORM\ManyToOne(inversedBy: 'userPosts')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  public function getId(): ?int {
    return $this->id;
  }

  public function getPostAt(): ?\DateTimeImmutable {
    return $this->postAt;
  }

  public function setPostAt(\DateTimeImmutable $postAt): self {
    $this->postAt = $postAt;

    return $this;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = $title;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;

    return $this;
  }

  public function getUser(): ?User {
    return $this->user;
  }

  public function setUser(?User $user): self {
    $this->user = $user;

    return $this;
  }
}
