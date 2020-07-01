<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{

    /**
     * @Route("/admin/article/new", name="admin_article_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $article->setAuthor('fjsdfjhfsf');
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('article_admin/new.html.twig',
            [
                'articleForm' => $form->createView()
            ]);

    }

    /**
     * @param ArticleRepository $repository
     * @return Response
     * @Route("admin/article/list", name="admin_article_list")
     */
    public function list(ArticleRepository $repository)
    {
        $articles = $repository->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/article/all", name="admin_article_all")
     */
    public function show(ArticleRepository $repository): Response
    {
        $articles = $repository->findAllOrderedByNewest();

        return $this->render('article/admin.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("admin/article{id}/delete", name="admin_article_delete")
     */
    public function deleteArticle(EntityManagerInterface $em, int $id): RedirectResponse
    {
        $article = $em->getRepository(Article::class)->find($id);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('admin_all');
    }


}