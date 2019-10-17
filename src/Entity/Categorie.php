<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", inversedBy="categories")
     */
    private $articleId;

    /**
     *
     * @ORM\Column(type="datetime")
     */
    private $created_at;



    public function __construct()
    {
        $this->articleId = new ArrayCollection();
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
     * @return Collection|Article[]
     */
    public function getArticleId(): Collection
    {
        return $this->articleId;
    }

    public function addArticleId(Article $articleId): self
    {
        if (!$this->articleId->contains($articleId)) {
            $this->articleId[] = $articleId;
        }

        return $this;
    }

    public function removeArticleId(Article $articleId): self
    {
        if ($this->articleId->contains($articleId)) {
            $this->articleId->removeElement($articleId);
        }

        return $this;
    }



    /**
     * @return datetime
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
