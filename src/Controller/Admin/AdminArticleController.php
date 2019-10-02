<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{
    private $repo;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ArticleRepository $repo, ObjectManager $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    /**
     * @Route("/admin/articles", name="admin.articles.index")
     */
    public function index()
    {
        $articles = $this->repo->findAll();

        return $this->render('admin/article/index.html.twig', [
            'current_menu' => 'admin',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin.article.edit", methods="GET|POST")
     */
    public function edit(Article $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid() ) {

            $this->em->flush();
            $this->addFlash("success","Modifier avec succés");
            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/create" , name="admin.article.create")
     */
    public function create(Request $request,Article $article)
    {
        $form = $this->createForm(ArticleType::class, $article);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash("success","Créer avec succés");
            return $this->redirectToRoute('article.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ], 301);
        }


        return $this->render('admin/article/create.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/article/delete/{id}" , name="admin.article.delete", methods="DELETE")
     */
    public function deleteProperty(Article $article ,Request $request)
    {

        if($this->isCsrfTokenValid('delete'.$article->getId(),$request->get('_token'))){
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash("success","Supprimer avec succés");
        }
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
            return $this->redirect($referer);


    }
}

