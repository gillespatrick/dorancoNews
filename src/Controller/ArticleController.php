<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * List Article
     * @Route("/", name="articles")
     * 
     * param converter
     */
    public function index(ArticleRepository $repo): Response
    {
        
         // $repo = $this ->getDoctrine() ->getRepository(Article::class);
          // $articles = $repo->findAll();
        //-------------------------------------------------------------------

          // Avec injection des dependances
          $articles = $repo->findAll([],['auteur' => 'DESC'], 12,0);


        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * Creer un article
     * @Route("/article/ajout", name="article_ajout")
     */
    public function add_article(Request $request, EntityManagerInterface $manager): Response
    {

        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'notice',
                "L'article a ete ajoute"
            );

            return $this->redirectToRoute('article_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


     /**
     * Detail Article
     * @Route("/article/{id}", name="article_show")
     * 
     * param converter
     */
    public function show(Article $article): Response
    {
        
    
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }


    /**
     * Supprimer Article Liste
     * @Route("/articles/delete", name="article_delete_list")
     * 
     * param converter
     */
    public function delete(ArticleRepository $repo): Response
    {
        
        $articles = $repo->findAll([],['auteur' => 'DESC']);


        return $this->render('article/delete.html.twig', [
            'articles' => $articles
        ]);

      
       
    }

     /**
     * Supprimer Article
     * @Route("/article/{id}/delete", name="article_delete")
     * 
     * param converter
     */
    public function delete_article(Article $article,EntityManagerInterface $manager): Response
    {
        
        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'notice',
            "L'article a ete supprime"
        );
        return $this->redirect($this->generateUrl('article_delete_list'));

      
       
    }

    /**
     * Modifier Article Liste
     * @Route("/articles/edit", name="article_edit_list")
     * 
     * param converter
     */
    public function edit(ArticleRepository $repo): Response
    {
        
        $articles = $repo->findAll([],['auteur' => 'DESC']);


        return $this->render('article/edit_list.html.twig', [
            'articles' => $articles
        ]);

      
       
    }


    /**
     * Supprimer Article
     * @Route("/article/{id}/edit", name="article_edit")
     * 
     * param converter
     */
    public function edit_article(Request $request, Article $article,EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'notice',
                "L'article a ete modifier"
            );

            return $this->redirectToRoute('article_edit_list', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
           
      
       
    }
}
