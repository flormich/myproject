<?php

namespace App\Entity;

use App\Repository\PicturesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PicturesRepository::class)
 */
class Pictures
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
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="pictures")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mainPicture;

    public function __toString()
    {
        return $this->getName();
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

    public function getArticle(): ?Articles
    {
        return $this->articles;
    }

    public function setArticle(?Articles $articles): self
    {
        $this->articles = $articles;

        return $this;
    }

    //     /**
    //  * @return Collection|articles[]
    //  */
    // public function getArticles(): Collection
    // {
    //     return $this->articles;
    // }

    // public function addArticle(articles $article): self
    // {
    //     if (!$this->articles->contains($article)) {
    //         $this->articles[] = $article;
    //     }

    //     return $this;
    // }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getMainPicture(): ?bool
    {
        return $this->mainPicture;
    }

    public function setMainPicture(bool $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }
}
