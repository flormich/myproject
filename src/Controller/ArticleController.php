<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Theme;
use App\Entity\Pictures;

use App\Form\AddArticleFormType;

use App\Repository\ArticlesRepository;
use App\Repository\PicturesRepository;
use App\Repository\ThemeRepository;
use App\Repository\KeyWordRepository;

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
     * @Route("/showArticle/{title}", name="show_one_article")
     */
    public function ShowOneArticle($title, ArticlesRepository $articleRepo, PicturesRepository $pictureRepo, ThemeRepository $themeRepo, KeyWordRepository $KeyWordRepo)
    {
        $article = $articleRepo->findOneBy(['title' => $title]);
        $pictures = $pictureRepo->findAll();
        $themes = $themeRepo->findAll();
        $keyWords = $KeyWordRepo->findAll();

        // return $this->render('articles/showOneArticle.html.twig', compact('article', 'picture'));
        return $this->render('articles/showOneArticle.html.twig', [
            'article' => $article,
            'pictures' => $pictures,
            'themes' => $themes,
            'keyWords' => $keyWords,
        ]);
    }

//     /**
//      * @Route("/showArticle/{id}", name="show_one_article")
//      */
//     public function ShowOneArticle($id, Request $request): Response
//     {
//         $entityManager = $this->getDoctrine()->getManager();
//         // $article = $entityManager->getRepository(Articles::class)->find($id);
//         // $theme = $articles->getThemes();
//         // $theme = $entityManager->getRepository(Theme::class)->find($id);
//         // $picture = $entityManager->getRepository(Pictures::class)->findBy(['articles' => '1']);
//         $picture = $entityManager->getRepository(Pictures::class)->findBy(['id' => '2']);

//         // var_dump($picture);

//         // var_dump($theme);

// //         $livre = $this->getDoctrine()->getRepository("AppBundle:Livre")->findOneByIsbn($isbn);
// // // équivaut à : $livre = $this->getDoctrine()->getRepository("AppBundle:Livre")->findOneBy(["isbn"=>$isbn]);
// // if(!$livre){
// //     // erreur livre non trouvé
// // }

// // $tags = $livre->getTags();


//         return $this->render('articles/showOneArticle.html.twig', [
//             // 'article' => $article,
//             // 'theme' => $theme,
//             'picture' => $picture,
//         ]);
//     }

    /**
     * @ROute("/updateArticle/{id}", name="update_article")
     */
    public function UpdateArticle(int $id, Articles $articles, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = $entityManager->getRepository(Articles::class)->find($id);
        $form = $this->createForm(AddArticleFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }

        return $this->render('articles/updateArticles.html.twig', [
            'updateArticleForm' => $form->createView(),
        ]);
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