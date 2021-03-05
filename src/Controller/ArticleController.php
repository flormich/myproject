<?php

namespace App\Controller;

use App\Entity\Articles;

use App\Form\AddArticleFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// include __DIR__ . '/../../assets/variable.php';

class ArticleController extends AbstractController
{
    /**
     * @Route("/addArticle", name="add_article")
     */
    public function AddArticle(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(AddArticleFormType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $article->setDateCreate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('articles/addArticle.html.twig', [
            'addArticleForm' => $form->createView(),
            // 'titreSite' => $_SESSION['titre'],
        ]);
    }

    /**
     * @Route("/showAllArticle", name="show_all_article")
     */
    public function ShowAllArticle(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['id' => 'DESC']);
        return $this->render('articles/showAllArticle.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/manageArticles", name="manage_articles")
     */
    public function ManageArticles(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['id' => 'DESC']);
        return $this->render('articles/manageArticles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/showArticle/{id}", name="show_one_article")
     */
    public function ShowOneArticle(Request $request): Response
    {
        
    }

    /**
     * @ROute("/updateArticle/{id}", name="update_article")
     */
    public function UpdateArticle(Request $request): Response
    {

    }

    /**
     * @Route("deleteArticle/{id}", name="delete_article")
     */
    public function DeleteArticle(Articles $article, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        // $request->getSession()
        //     ->getFlashBag()
        //     ->add('action', 'Supression réussi');
        
        return $this->redirectToRoute('manage_articles');
    }
}