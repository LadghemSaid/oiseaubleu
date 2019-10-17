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
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="votings")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="votings")
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $likeComment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dislikeComment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLikeComment(): ?bool
    {
        return $this->likeComment;
    }

    public function setLikeComment(?bool $likeComment): self
    {
        $this->likeComment = $likeComment;

        return $this;
    }

    public function getDislikeComment(): ?bool
    {
        return $this->dislikeComment;
    }

    public function setDislikeComment(?bool $dislikeComment): self
    {
        $this->dislikeComment = $dislikeComment;

        return $this;
    }
}
