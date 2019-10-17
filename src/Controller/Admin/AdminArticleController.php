<?php

namespace App\Controller\Admin;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Form\CategorieType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class AdminArticleController extends AbstractController
{
    private $repoArticle;
    private $repoCategori;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ArticleRepository $repo,CategorieRepository $repoCategori, ObjectManager $em)
    {
        $this->repoArticle = $repo;
        $this->repoCategori = $repoCategori;
        $this->em = $em;
    }

    /**
     * @Route("/admin/articles", name="admin.articles.index")
     */
    public function indexArticle()
    {
        $articles = $this->repoArticle->findAll();

        return $this->render('admin/article/index.html.twig', [
            'current_menu' => 'admin',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin.article.edit", methods="GET|POST")
     */
    public function editArticle(Article $article, Request $request)
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
     * @Route("/admin/article/delete/{id}" , name="admin.article.delete", methods="DELETE")
     */
    public function deleteArticle(Article $article ,Request $request)
    {

        if($this->isCsrfTokenValid('delete'.$article->getId(),$request->get('_token'))){
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash("success","Supprimer avec succés");
        }
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
            return $this->redirect($referer);


    }

    /**
     * @Route("/admin/article/create" , name="admin.article.create")
     * @param Security $security
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function createArticle(Request $request,Security $security)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $article->setAuthor($security->getUser());
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
     * @Route("/admin/categorie/create" , name="admin.categorie.create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function createCategorie(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid()) {
            $categorie->setCreatedAt(new \DateTime());
            $this->em->persist($categorie);
            $this->em->flush();
            $this->addFlash("success","Créer avec succés");
            return $this->redirectToRoute('admin.categorie.index');
        }


        return $this->render('admin/categorie/create.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/admin/categorie/{id}", name="admin.categorie.edit", methods="GET|POST")
     */
    public function editCategorie(Categorie $categorie, Request $request)
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        if ($form->handleRequest($request) && $form->isSubmitted() && $form->isValid() ) {
            $categorie->setCreatedAt(new \DateTime());
            $this->em->flush();
            $this->addFlash("success","Modifier avec succés");
            return $this->redirectToRoute('admin.categorie.index');
        }

        return $this->render('admin/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categorie/delete/{id}" , name="admin.categorie.delete", methods="DELETE")
     */
    public function deleteCategorie(Categorie $categorie,Request $request)
    {

        if($this->isCsrfTokenValid('delete'.$categorie->getId(),$request->get('_token'))){
            $this->em->remove($categorie);
            $this->em->flush();
            $this->addFlash("success","Supprimer avec succés");
        }
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        return $this->redirect($referer);


    }

    /**
     * @Route("/admin/categorie", name="admin.categorie.index")
     */
    public function indexCategorie()
    {
        $categories = $this->repoCategori->findAll();

        return $this->render('admin/categorie/index.html.twig', [
            'current_menu' => 'admin',
            'categories' => $categories,
        ]);
    }

}

