<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="home")
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findLatest();
        $catedories = Article::CATEGORIE;


        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'categories' => $catedories,

        ]);
    }
    
}
