<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentsController extends AbstractController
{
    /**
     * @Route("/add/comment/{article}", name="add.comment", methods={"POST"})
     * @param Request $req
     * @param Security $security
     * @param $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function add(Request $req,Security $security, $article,ArticleRepository $articleRepo)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $com = $form->getData();
            $user = $security->getUser();
            $com->setUser($user);
            $com->setArticle($articleRepo->find($article));
            $com->setCreatedAt(new \DateTime());
            $com->setApproved(true);
            //dd($com);
            $em =  $this->getDoctrine()->getManager();
            $em->persist($com);
            $em->flush();
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!

            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('article.index', array('page'=>'1'));
        }
    }

    /**
     * @Route("/delete/comment", name="delete.comment", methods={"DELETE"})
     */
    public function delete(Request $req)
    {

    }


}
