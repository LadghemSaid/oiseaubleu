<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingController extends AbstractController
{

    /**
     * @Route("/like{article}", name="voting.like")
     */
    public function likeArticle(Article $article)
    {

    }

    /**
     * @Route("/dislike/{article}", name="voting.dislike")
     */
    public function dislikeArticle(Article $article)
    {

    }

}
