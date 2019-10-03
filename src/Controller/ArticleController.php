<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArticleController extends AbstractController
{
    private $repository;
    public function __construct(ArticleRepository $property_repo)
    {
        $this->repository = $property_repo;
    }

    /**
     * Liste l'ensemble des articles triés par date de publication pour une page donnée.
     *
     * @Route("/articles/page/{page}", requirements={"page" = "\d+"}, name="article.index")
     * @Template("XxxYyyBundle:Front/Article:index.html.twig")
     *
     * @param int $page Le numéro de la page
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($page)
    {
        $nbArticlesParPage = 9;

        $articles = $this->repository->findAllPagineEtTrie($page,$nbArticlesParPage);
        $catedories = Article::CATEGORIE;

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($articles)/ $nbArticlesParPage),
            'nomRoute' => 'article.index',
            'paramsRoute' => array()
            );

        return $this->render('article/index.html.twig', [
            'current_menu' => 'articles',
            'articles' => $articles,
            'pagination' => $pagination,
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
