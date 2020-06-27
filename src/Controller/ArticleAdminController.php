<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{

    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em)
    {
        $article1 = new Article();
        $article1->setTitle('What is wrong with homeopathy')
            ->setImageFilename('images/justWater.jpeg')
            ->setAuthor('Grigory')
            ->setContent('
                         Spicy jalapeno bacon ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
                         lorem proident beef ribs aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
                         labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
                         turkey shank eu pork belly meatball non cupim.
                         Do mollit deserunt prosciutto laborum. Duis sint tongue quis nisi. Capicola qui beef ribs dolore pariatur.
                         Minim strip steak fugiat nisi est, meatloaf pig aute. Swine rump turducken nulla sausage. Reprehenderit pork
                         belly tongue alcatra, shoulder excepteur in beef bresaola duis ham bacon eiusmod. Doner drumstick short loin,
                         adipisicing cow cillum tenderloin.');


        if (rand(1,10) > 2) {
            $article1->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
            $em->persist($article1);

            $article2 = new Article();
            $article2->setAuthor('James James')
                     ->setTitle('Antibiotics: An overview')
                     ->setContent('Antibiotics are a type of medicine which are used to treat bacterial infections.
                      To help the immune system, we sometimes use antibiotics, which are chemicals (specifically a swarm of small molecules)
                       that enter and stick to important parts (think of targets) of the bacterial cell, and interfere with its ability
                        to survive and multiply. If the bacteria are susceptible to the antibiotic, then they will stop growing or simply die.')
                      ->setImageFilename('images/antibiotics.jpg')
                      ->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
            $em->persist($article2);
            $article3 = new Article();
             $article3->setAuthor('James Johnson')
                ->setTitle('Ebola: At a glance')
                ->setContent('Ebola disease is a life-threatening illness caused by the Ebola virus.In December 2013,
                 a 2-year-old toddler died in a rural village in Guinea, sparking the largest Ebola outbreak the world has
                  ever known (Baize et al., 2014). The outbreak primarily involves three countries in Western Africa: Guinea,
                   Liberia, and Sierra Leone (although there have been additional cases/deaths in other countries).
                 As of early November 2014, there have been over 13,000 cases and almost 5,000 deaths, although experts
                  believe that these numbers could be 250% greater as many patients never seek medical assistance.')
                ->setImageFilename('images/ebola.png')
                ->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
            $em->persist($article3);

            $comment1 = new Comment();
            $comment1->setAuthorName('Mike Black');
            $comment1->setContent('oooh, that is soo amazing!!!');
            $comment1->setArticle($article1);
            $comment1->setArticle($article3);
            $em->persist($comment1);

            $comment2 = new Comment();
            $comment2->setAuthorName('Papusica');
            $comment2->setContent('uhhh, la-la');
            $comment2->setArticle($article1);
            $comment2->getArticle($article2);
            $em->persist($comment2);

            $em->flush();
        }
      return new Response('You have just added a new article!!!');

    }

        /**
         * @Route("/admin/article/all", name="app_admin")
         */
        public function show(ArticleRepository $repository)
    {
        $articles = $repository->findAllOrderedByNewest();
        return $this->render('article/admin.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete")
     */
    public function deleteArticle(EntityManagerInterface $em, int $id )
    {
      $article = $em->getRepository(Article::class)->find($id);
       $em->remove($article);
       $em->flush();

        return $this->redirectToRoute('app_admin');
    }


}