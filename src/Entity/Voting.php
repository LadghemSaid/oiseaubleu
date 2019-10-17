<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VotingRepository")
 */
class Voting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="votings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $articleId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $likeArticle;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dislikeArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getArticleId(): ?Article
    {
        return $this->articleId;
    }

    public function setArticleId(?Article $articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLikeArticle(): ?bool
    {
        return $this->likeArticle;
    }

    public function setLikeArticle(?bool $likeArticle): self
    {
        $this->likeArticle = $likeArticle;

        return $this;
    }

    public function getDislikeArticle(): ?bool
    {
        return $this->dislikeArticle;
    }

    public function setDislikeArticle(?bool $dislikeArticle): self
    {
        $this->dislikeArticle = $dislikeArticle;

        return $this;
    }
}
