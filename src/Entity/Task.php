<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table]
class Task
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private $createdAt;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(message: "Vous devez saisir un titre.")]
    private $title;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Vous devez saisir du contenu.")]
    private $content;

    #[ORM\Column(type: Types::BOOLEAN)]
    private $isDone;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $author = null;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function isDone()
    {
        return $this->isDone;
    }

    public function toggle($flag)
    {
        $this->isDone = $flag;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
