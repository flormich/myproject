<?php

namespace App\Entity;

use App\Repository\ThemesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemesRepository::class)
 */
class Themes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ArticlesThemes::class, mappedBy="themes", orphanRemoval=true)
     */
    private $articlesThemes;

    public function __construct()
    {
        $this->Themes = new ArrayCollection();
        $this->articlesThemes = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
        // return (string) $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ArticlesThemes[]
     */
    public function getArticlesThemes(): Collection
    {
        return $this->articlesThemes;
    }

    public function addArticlesTheme(ArticlesThemes $articlesTheme): self
    {
        if (!$this->articlesThemes->contains($articlesTheme)) {
            $this->articlesThemes[] = $articlesTheme;
            $articlesTheme->setThemes($this);
        }

        return $this;
    }

    public function removeArticlesTheme(ArticlesThemes $articlesTheme): self
    {
        if ($this->articlesThemes->removeElement($articlesTheme)) {
            // set the owning side to null (unless already changed)
            if ($articlesTheme->getThemes() === $this) {
                $articlesTheme->setThemes(null);
            }
        }

        return $this;
    }
}
