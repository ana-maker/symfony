<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends AbstractController
{
    /**
     * @param CommentRepository $commentRepository
     * @return Response
     * @Route("/admin/comment/all", name="admin_comment_all")
     */
    public function index(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('comment_admin/index.html.twig', [
            'comments' => $comments
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param int $id
     * @return RedirectResponse
     * @Route("/admin/comment{id}/delete", name="admin_comment_delete")
     */
    public function deleteComment(EntityManagerInterface $em, int $id): RedirectResponse
    {
        $comment = $em->getRepository(Comment::class)->find($id);
        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('admin_comment_all');
    }

    /**
     * @param EntityManagerInterface $em
     * @param Article $article
     * @param Request $request
     * @return Response
     * @Route("/admin/comment/add{id}", name="admin_comment_add")
     */
    public function newComment(EntityManagerInterface $em, Article $article, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('article_show', [
                'article' => $article->getId(),
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('comment_admin/form.html.twig', [

            'commentForm' => $form->createView(),
            'article' => $article
        ]);
    }
}