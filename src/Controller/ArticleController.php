<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllOrderedByNewest();
        return $this->render('article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);

    }

    /**
     * @Route("/newest", name="app_newest")
     */
    public function newest(ArticleRepository $repository)
    {
        $articles = $repository->findAllNewest();
        return $this->render('article/newest.html.twig', [
            'articles' => $articles
        ]);
    }
}