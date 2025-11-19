<?php
// src/Entity/Testimonial.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['testimonial:read']],
    denormalizationContext: ['groups' => ['testimonial:write']]
)]
#[ORM\Entity]
#[ORM\Table(name: 'testimonials')]
class Testimonial
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['testimonial:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups(['testimonial:read', 'testimonial:write'])]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['testimonial:read', 'testimonial:write'])]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['testimonial:read', 'testimonial:write'])]
    private ?string $role = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Groups(['testimonial:read', 'testimonial:write'])]
    private ?string $avatar = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(['farmer', 'builder', 'health'])]
    #[Groups(['testimonial:read', 'testimonial:write'])]
    private ?string $avatarType = null;

    #[ORM\Column]
    #[Groups(['testimonial:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['testimonial:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAvatarType(): ?string
    {
        return $this->avatarType;
    }

    public function setAvatarType(string $avatarType): static
    {
        $this->avatarType = $avatarType;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
