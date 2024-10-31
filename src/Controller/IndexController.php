<?php

namespace App\Controller;

use App\Entity\Article; // Importation de l'entité Article
use Doctrine\ORM\EntityManagerInterface; // Importation d'EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Importing Request
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Importing TextType
use Symfony\Component\Form\Extension\Core\Type\NumberType; // Importing NumberType
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Importing SubmitType
use App\Form\ArticleType;
use App\Entity\Categorie;
use App\Form\CategorieType;

class IndexController extends AbstractController
{
    #[Route('/article/{id}', name: 'article_show', requirements: ['id' => '\d+'])]
public function show(EntityManagerInterface $entityManager, int $id): Response
{
    $article = $entityManager->getRepository(Article::class)->find($id);
    
    if (!$article) {
        throw $this->createNotFoundException('No article found for id ' . $id);
    }

    return $this->render('articles/show.html.twig', [
        'article' => $article,
    ]);
}


    #[Route('/article/save', name: 'article_save')]
    public function save(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(1000); // Assurez-vous que le prix est un entier ou un décimal.
        
        $em->persist($article);
        $em->flush();

        return new Response('Article enregistré avec id ' . $article->getId());
    }

    #[Route('/articles', name: 'article_list')]
    public function listArticles(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Save changes to the database
            return $this->redirectToRoute('article_list'); // Redirects to the list of articles
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }


    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/delete/{id}', name: 'article_delete', methods: ['POST', 'DELETE'])]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('No article found for id ' . $id);
        }

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('article_list');
    }

    #[Route('/categorie/new', name: 'new_categorie', methods: ["GET", "POST"])]
    public function newCategorie(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('categorie/newCategorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
