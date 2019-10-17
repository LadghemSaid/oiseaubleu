<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Voting;
use App\Form\CommentType;
use App\Form\VotingType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArticleController extends AbstractController
{
    private $repository;
    private $repository_user;
    /**
     * @var Request
     */
    private $request;

    public function __construct(ArticleRepository $property_repo, UserRepository $users)
    {
        $this->repository = $property_repo;
        $this->repository_user = $users;
        $this->request = Request::createFromGlobals();
    }

    /**
     * Liste l'ensemble des articles triés par date de publication pour une page donnée.
     *
     * @Route("/articles/page/{page}/{filter}", name="article.index")
     * @Template("XxxYyyBundle:Front/Article:index.html.twig")
     *
     * @param int $page Le numéro de la page
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($page = 1, $filter = '{"":}')
    {
        $req = $this->request;
        $nbArticlesParPage = 9; //Nombre d'articles à afficher sur chaque page
        $filter = json_decode($filter); //On défini le filtre comme un tableau ('nomFiltre'=>valeur)

        if ($filter == null) { //Si il n'y a pas de filtre existant en entrée
            //LISTE DES FILTRES
            $titre = $req->request->get('title'); //Recupération du titre
            if ($titre != null && $titre != "") { //Si l'utilisateur a entré un titre non vide
                $filter['title'] = $titre;
            }
        }

        $currentSearch = json_encode($filter, JSON_UNESCAPED_UNICODE); //On encode les filtres en JSON pour sauvegarder la recherche (pagination)

        $search = $filter['title'];

        $author = $this->repository_user->findByLogin($search);


        if($author === null || sizeof($author)<1){
            $author = null;
        }else{
            $author = $author[0]->getId();
        }


        $articles = $this->repository->findAllPagineEtTrie($page, $nbArticlesParPage, $filter, $author); //On récupère les articles


        $catedories = Article::CATEGORIE;

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($articles) / $nbArticlesParPage),
            'nomRoute' => 'article.index',
            'filter' => $currentSearch,
        );
        return $this->render('article/index.html.twig', [
            'current_menu' => 'articles',
            'articles' => $articles,
            'pagination' => $pagination,
            'categories' => $catedories,
        ]);
    }

    /**
     * @Route("/article/{slug}/{id}" , name="article.show", requirements={"id"="\d+","slug"="[a-z0-9\-]*"})
     * @param Article $article
     * @param string $slug
     * @param CommentRepository $commentsRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Article $article, string $slug, CommentRepository $commentsRepository)
    {
        if ($article->getSlug() !== $slug) {

            return $this->redirectToRoute('article.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()

            ], 301);
        }

        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('add.comment', array('article' => $article->getId())),
        ]);





        $article = $this->repository->find($article);
        $comments = $article->getComments();
        //dd($comments);
        $catedories = Article::CATEGORIE;

        return $this->render('article/show.html.twig', [
            'current_menu' => 'articles',
            'article' => $article,
            'categories' => $catedories,
            'formComment' => $formComment->createView(),

            'comments' => $comments

        ]);

    }

    /**
     * @Route("/" , name="index")
     *
     */
    public function home()
    {
        return $this->index();
    }
}
