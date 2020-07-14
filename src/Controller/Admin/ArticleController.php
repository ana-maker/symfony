<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController
 * @package App\Controller\Admin
 */
class ArticleController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @Route("/admin/article/new", name="admin_article_new")
     */
    public function newArticle(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $path = $this->getParameter('kernel.project_dir') . '/public/images';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = 'images/' . $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move($path, $newFilename);

                $article->setImageFilename($newFilename);
            }

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
    public function list(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param int $id
     * @return RedirectResponse
     * @Route("admin/article/{id}/delete", name="admin_article_delete")
     */
    public function deleteArticle(EntityManagerInterface $em, int $id): RedirectResponse
    {
        $article = $em->getRepository(Article::class)->find($id);

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('admin_article_list');
    }

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param Article $article
     * @return Response
     * @Route("admin/article/{id}/update", name="admin_article_update")
     */
    public function updateArticle(EntityManagerInterface $em, Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $path = $this->getParameter('kernel.project_dir') . '/public/images';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = 'images/' . $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move($path, $newFilename);

                $article->setImageFilename($newFilename);
            }

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_list', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article_admin/update.html.twig', [
            'articleForm' => $form->createView(),
            'id' => $article->getId()
        ]);
    }

}
