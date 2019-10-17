<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Voting;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class VotingController extends AbstractController
{
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/toggleLike/article/{article}", name="voting.article.togglelike")
     */
    public function toggleLikeArticle(Article $article, Security $security)
    {
        $vote = new Voting();
        $vote->setArticle($article)
            ->setLikeArticle(true)
            ->setCreatedAt(new \DateTime())
            ->setUser($security->getUser());
        $this->em->persist($vote);
        $this->em->flush();
        //dd($vote);
        return $this->redirectToRoute('article.show', array('slug'=>$article->getSlug(),'id'=>$article->getId()));

    }

    /**
     * @Route("/dislike/article/{article}", name="voting.article.dislike")
     */
    public function dislikeArticle(Article $article,Security $security)
    {
        $vote = new Voting();
        $vote->setArticle($article)
            ->setDislikeArticle(true)
            ->setUser($security->getUser());
        $this->em->persist();
        $this->em->flush();
    }

    /**
     * @Route("/like/comment/{comment}", name="voting.comment.like")
     */
    public function likeComment(Comment $comment,Security $security)
    {
        $vote = new Voting();
        $vote->setComment($comment)
            ->setLikeComment(true)
            ->setUser($security->getUser());
        $this->em->persist();
        $this->em->flush();


    }

    /**
     * @Route("/dislike/comment/{comment}", name="voting.comment.dislike")
     */
    public function dislikeComment(Comment $comment,Security $security)
    {
        $vote = new Voting();
        $vote->setComment($comment)
            ->setDislikeComment(true)
            ->setUser($security->getUser());
        $this->em->persist();
        $this->em->flush();

    }

}
