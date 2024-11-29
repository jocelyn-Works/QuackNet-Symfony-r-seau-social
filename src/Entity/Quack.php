<?php

namespace App\Entity;

use App\Repository\QuackRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: QuackRepository::class)]
class Quack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "ce champs ne peut etre vide")]
    #[Assert\Length( min : 5, minMessage: 'Veuillez détailler votre Quack')]
    private ?string $content = null;

    #[ORM\Column( nullable:true)]
    private ?string $picture = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'quacks')]
    private ?Duck $author = null;

    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Quack $parent = null;

    #[ORM\OneToMany(targetEntity: Quack::class, mappedBy: 'parent')]
    private Collection $children;

    public function getParent(): ?Quack
    {
        return $this->parent;
    }

    public function setParent(?Quack $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): void
    {
        $this->children = $children;
    }



    public function getAuthor(): ?Duck
    {
        return $this->author;
    }

    public function setAuthor(?Duck $author): void
    {
        $this->author = $author;
    }


    /**
     */
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }



    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }





}
