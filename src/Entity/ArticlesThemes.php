<?php

namespace App\Entity;

use App\Repository\ArticlesThemesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesThemesRepository::class)
 */
class ArticlesThemes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="articlesThemes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $articles;

    /**
     * @ORM\ManyToOne(targetEntity=Themes::class, inversedBy="articlesThemes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $themes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticles(): ?Articles
    {
        return $this->articles;
    }

    public function setArticles(?Articles $articles): self
    {
        $this->articles = $articles;
        return $this;
    }

    public function getThemes(): ?Themes
    {
        return $this->themes;
    }

    public function setThemes(?Themes $themes): self
    {
        $this->themes = $themes;
        return $this;
    }
}
