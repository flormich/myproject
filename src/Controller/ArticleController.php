<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Themes;
use App\Entity\KeyWord;
use App\Entity\Pictures;
use App\Entity\ArticlesThemes;
use App\Entity\Users;

use App\Form\AddArticleFormType;
use App\Form\AddThemeFormType;
use App\Form\AddKeyWordsFormType;
use App\Form\AddPictureFormType;
use Symfony\Component\Form\Form;

use App\Repository\ArticlesRepository;
use App\Repository\ThemesRepository;
use App\Repository\KeyWordRepository;
use App\Repository\PicturesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

// include __DIR__ . '/../../assets/variable.php';

class ArticleController extends AbstractController
{
    private function addKey(Form $form): Form
    {
        return $form->add("name", EntityType::class,[
            "label" => "Mot clé",
            "class" => KeyWord::class,
            "choice_label" => "name",
            "expanded" => true,
            "multiple" => false,
            "required" => false,
        ]);
    }

    private function addThemes(Form $form): Form
    {
        return $form->add("name", EntityType::Class,[
            "label" => "Theme : ",
            "class" => Themes::Class,
            "choice_label" => "name",
            "expanded" => false,
            "multiple" => false,
            "required" => true,
        ]);
    }


    /**
     * @Route("/addArticle", name="add_article")
     */
    public function AddArticle(Request $request): Response
    {
        $article = new Articles();
        $formArticle = $this->createForm(AddArticleFormType::class, $article);
        $formArticle->handleRequest($request);   
        $articleTitle = $article->getTitle();

        $theme = new Themes();
        $formTheme = $this->createForm(AddThemeFormType::class, $theme);
        $this->addThemes($formTheme);
        $formTheme->handleRequest($request);
        //Récupére les données Id pour le persisté dans la table Join
        $idTheme = $this->getDoctrine()->getManager()->getRepository(Themes::class)->findOneBy(array('name' => $theme->getName()));

        $picture = new Pictures();
        $formPicture = $this->createForm(AddPictureFormType::class, $picture);
        $formPicture->handleRequest($request);

        $articlesThemes = new ArticlesThemes();
        $articlesThemes->setThemes($idTheme);
        $articlesThemes->setArticles($article);    

        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setDateCreate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $article->setDateUpdate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $picture->setArticle($article);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);   
            $entityManager->persist($picture);   
            $entityManager->persist($articlesThemes);
            $entityManager->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('action', 'L\' enregistrement de : ' . $articleTitle .  ' a réussi');
            return $this->redirectToRoute('manage_articles');
        }

        return $this->render('articles/addArticle.html.twig', [
            'addArticleForm' => $formArticle->createView(),
            'addThemeForm' => $formTheme->createView(),
            'addPictureForm' => $formPicture->createView(),
            // 'addKeyForm' => $form3->createView(),
            // 'titreSite' => $_SESSION['titre'],
        ]);
    }

    /**
     * @Route("/", name="show_all_article")
     */
    public function ShowAllArticle(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['id' => 'DESC']);
        $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findAll();

        return $this->render('articles/showAllArticle.html.twig', [
            'articles' => $articles,
            'pictures' => $pictures,
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
    public function ShowOneArticle($title, ArticlesRepository $articleRepo, PicturesRepository $pictureRepo, ThemesRepository $themeRepo, KeyWordRepository $KeyWordRepo)
    {
        $article = $articleRepo->findOneBy(['title' => $title]);
        $pictures = $pictureRepo->findAll();
        // $themes = $themeRepo->findAll();
        $themes = $article->getArticlesThemes();
        $keyWords = $article->getKeyWords();
        // $keyWords = $KeyWordRepo->findAll();

        // return $this->render('articles/showOneArticle.html.twig', compact('article', 'picture'));
        return $this->render('articles/showOneArticle.html.twig', [
            'article' => $article,
            'pictures' => $pictures,
            'themes' => $themes,
            'keyWords' => $keyWords,
        ]);
    }

    /**
     * @ROute("/updateArticle/{title}", name="update_article")
     */
    public function UpdateArticle($title, Articles $article, Request $request): Response
    {
        
        // $entityManager = $this->getDoctrine()->getManager();

        // $article = $entityManager->getRepository(Articles::class)->find($title);
        $form = $this->createForm(AddArticleFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setDateUpdate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('show_all_article');
        }

        return $this->render('articles/updateArticles.html.twig', [
            'updateArticleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("deleteArticle/{title}", name="delete_article")
     */
    public function DeleteArticle(Articles $article, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $articleTitle = $article->getTitle();

        $em->remove($article);
        $em->flush();

        $request->getSession()
            ->getFlashBag()
            ->add('action', 'La supression de ' . $articleTitle .  ' a réussi');
        
        return $this->redirectToRoute('manage_articles');
    }
}