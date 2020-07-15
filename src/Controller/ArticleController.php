<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{
    /**
     * @param ArticleRepository $repository
     *
     * @return Response
     *
     * @Route("/", name="news_homepage")
     */
    public function homepage(ArticleRepository $repository): Response
    {
        $articles = $repository->findAllOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param Article $article
     *
     * @return Response
     *
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @param ArticleRepository $repository
     *
     * @return Response
     *
     * @Route("/newest", name="news_newest")
     */
    public function newest(ArticleRepository $repository): Response
    {
        $articles = $repository->findAllNewest();

        return $this->render('article/newest.html.twig', [
            'articles' => $articles
        ]);
    }
}