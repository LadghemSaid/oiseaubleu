<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingController extends AbstractController
{

    /**
     * @Route("/like{article}", name="voting.like")
     */
    public function likeArticle()
    {
        return $this->render('voting/index.html.twig', [
            'controller_name' => 'VotingController',
        ]);
    }

    /**
     * @Route("/dislike/{id}", name="voting.dislike")
     */
    public function dislikeArticle()
    {
        return $this->render('voting/index.html.twig', [
            'controller_name' => 'VotingController',
        ]);
    }

}
