<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $repository;
    public function __construct(ArticleRepository $property_repo)
    {
        $this->repository = $property_repo;
    }

    /**
     * @Route("/articles", name="article.index")
     */
    public function index()
    {
        $articles = $this->repository->findAllVisible();
        $catedories = Article::CATEGORIE;

        return $this->render('article/index.html.twig', [
            'current_menu' => 'articles',
            'articles' => $articles,
            'categories' => $catedories,
        ]);
    }

    /**
     * @Route("/article/{slug}-{id}" , name="article.show", requirements={"id"="\d+","slug"="[a-z0-9\-]*"})
     */
    public function show(Article $article, string $slug){
        if($article->getSlug()!==$slug){
        return $this->redirectToRoute('article.show',[
            'id' => $article->getId(),
            'slug'=>$article->getSlug()
        ],301);
        }
        $article = $this->repository->find($article);
        $catedories = Article::CATEGORIE;
        return $this->render('article/show.html.twig', [
            'current_menu' => 'articles',
            'article' => $article,
            'categories' => $catedories
        ]);

    }
}
