<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @ORM\ManyToMany(targetEntity=KeyWord::class, mappedBy="articles")
     */
    private $keyWords;

    /**
     * @ORM\OneToMany(targetEntity=Pictures::class, mappedBy="articles")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity=ArticlesThemes::class, mappedBy="articles")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $articlesThemes;

    public function __construct()
    {
        $this->keyWords = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->articlesThemes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->dateCreate;
    }

    public function setDateCreate(\DateTimeInterface $dateCreate): self
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @return Collection|KeyWord[]
     */
    public function getKeyWords(): Collection
    {
        return $this->keyWords;
    }

    public function addKeyWord(KeyWord $keyWord): self
    {
        if (!$this->keyWords->contains($keyWord)) {
            $this->keyWords[] = $keyWord;
            $keyWord->addArticle($this);
        }

        return $this;
    }

    public function removeKeyWord(KeyWord $keyWord): self
    {
        if ($this->keyWords->removeElement($keyWord)) {
            $keyWord->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection|Pictures[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Pictures $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setArticle($this);
        }

        return $this;
    }

    public function removePicture(Pictures $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getArticle() === $this) {
                $picture->setArticle(null);
            }
        }

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
            $articlesTheme->setArticles($this);
        }

        return $this;
    }

    public function removeArticlesTheme(ArticlesThemes $articlesTheme): self
    {
        if ($this->articlesThemes->removeElement($articlesTheme)) {
            // set the owning side to null (unless already changed)
            if ($articlesTheme->getArticles() === $this) {
                $articlesTheme->setArticles(null);
            }
        }

        return $this;
    }
}
