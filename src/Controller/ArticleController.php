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

        $image = new Pictures();
        $formPicture = $this->createForm(AddPictureFormType::class, $image);
        $formPicture->handleRequest($request);

        $articlesThemes = new ArticlesThemes();
        $articlesThemes->setThemes($idTheme);
        $articlesThemes->setArticles($article);    

        if ($formArticle->isSubmitted() && $formArticle->isValid()){
            $article->setDateCreate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $article->setDateUpdate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            // $picture->setArticle($article);
            
            // On récupère les images transmises
            $mainImage = $formArticle->get('picturesMain')->getData();
            $images = $formArticle->get('pictures')->getData();

            // Image principale
            $fichierPrincipale = md5(uniqid()).'.'.$mainImage->guessExtension();
            $mainImage->move(
                $this->getParameter('images_directory'),
                $fichierPrincipale
            );
            $mainPicture = new Pictures();
                $mainPicture->setAddress($fichierPrincipale);
                $mainPicture->setName($image);
                $mainPicture->setArticle($article); 
                $mainPicture->setMainPicture('1');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($mainPicture);
                

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                              
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $picture = new Pictures();
                $picture->setAddress($fichier);
                $picture->setName('Name');
                $picture->setArticle($article); 
                $picture->setMainPicture('0');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($picture);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);     
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
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['dateUpdate' => 'DESC']);
        // $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findAll();
        $mainPictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findBy(['mainPicture' => "true"]);
        // var_dump($mainPictures);

        return $this->render('articles/showAllArticle.html.twig', [
            'articles' => $articles,
            // 'pictures' => $pictures,
            'mainPictures' => $mainPictures,
        ]);
    }

    /**
     * @Route("/manageArticles", name="manage_articles")
     */
    public function ManageArticles(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['id' => 'DESC']);
        $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findAll();
        $themes = $this->getDoctrine()->getManager()->getRepository(Themes::class)->findAll();

        return $this->render('articles/manageArticles.html.twig', [
            'articles' => $articles,
            'pictures' => $pictures,
            'themes' => $themes,
        ]);
    }

    /**
     * @Route("/showArticle/{title}", name="show_one_article")
     */
    public function ShowOneArticle($title, ArticlesRepository $articleRepo, PicturesRepository $pictureRepo, ThemesRepository $themeRepo, KeyWordRepository $KeyWordRepo)
    {
        $article = $articleRepo->findOneBy(['title' => $title]);
        // $pictures = $pictureRepo->findAll();
        $pictures = $pictureRepo->findBy(['articles' => $article->getId()]);
        // $mainPictures = $pictureRepo->findOneBy(['id' => '46']);
        // $mainPicture = $pictureRepo->findBy(['articles' => $article->getId()], ['mainPicture' => 'true']);
        $themes = $article->getArticlesThemes();
        $keyWords = $article->getKeyWords();

        // $idArticle = $article->getId();
        // $idArticlePicture = $article->getPictures();
        // var_dump($idArticle);
        // var_dump($idArticlePicture);
        // var_dump($pictures);

        // if ( $article->getId() == $picturesarticle.id )
        $output = array();
        foreach($pictures as $event)
            {
                $output[$event->getId()] = $event->getAddress();
                $arrayId[] = $event->getAddress();
            }

        if (isset($arrayId[0])) {
        } else {
            $arrayId[0] = 'nophoto';
        }

        if (isset($arrayId[1])) {
        } else {
            $arrayId[1] = 'nophoto';
        }

        if (isset($arrayId[2])) {
        } else {
            $arrayId[2] = 'nophoto';
        }

        if (isset($arrayId[3])) {
        } else {
            $arrayId[3] = 'nophoto';
        }

        // if (isset($mainPicture)) {
        //     $mainPicture = $mainPicture->getAddress();
        // } else {
        //     $mainPicture = 'nophoto';
        // }

        return $this->render('articles/showOneArticle.html.twig', [
            'article' => $article,
            'pictures' => $pictures,
            'themes' => $themes,
            'keyWords' => $keyWords,
            // 'mainPicture' => $mainPicture,
            'outputs1' => $arrayId[0],
            'outputs2' => $arrayId[1],
            'outputs3' => $arrayId[2],
            'outputs4' => $arrayId[3],
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

        $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findBy(['articles' => $article->getId()]);
        $mainPictures = $pictures[0];

        if($form->isSubmitted() && $form->isValid())
        {
            // On récupère les images transmises
            $mainImage = $form->get('picturesMain')->getData();
            $images = $form->get('pictures')->getData();

            // Image principale
            $fichierPrincipale = md5(uniqid()).'.'.$mainImage->guessExtension();
            $mainImage->move(
                $this->getParameter('images_directory'),
                $fichierPrincipale
            );
            $mainPicture = new Pictures();
                $mainPicture->setAddress($fichierPrincipale);
                $mainPicture->setName('Name');
                $mainPicture->setArticle($article); 
                $mainPicture->setMainPicture('1');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($mainPicture);
                

            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                              
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // On crée l'image dans la base de données
                $picture = new Pictures();
                $picture->setAddress($fichier);
                $picture->setName('Name');
                $picture->setArticle($article); 
                $picture->setMainPicture('0');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($picture);
            }

            $article->setDateUpdate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('show_all_article');
        }

        return $this->render('articles/updateArticles.html.twig', [
            'updateArticleForm' => $form->createView(),
            'pictures' => $pictures,
            'mainPictures' => $mainPictures,
        ]);
    }

    /**
     * @Route("deleteArticle/{title}", name="delete_article")
     */
    public function DeleteArticle(Articles $article, PicturesRepository $pictureRepo, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $articleTitle = $article->getTitle();
        // $articleId = $article->getId();

        $picture = new Pictures();
        $em->remove($article);
        $em->remove($picture);
        $em->flush();

        $AddressPicture = $pictureRepo->findBy(['articles' => $article->getId()]);
        $lengthTab = count($AddressPicture);
        for ($i=0; $i<$lengthTab; $i++){
            // $affichage[] = $AddressPicture[$i]->getAddress();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$AddressPicture[$i]->getAddress());
        }

        $request->getSession()
            ->getFlashBag()
            ->add('action', 'La supression de ' . $articleTitle .  ' a réussi');
        
        // return ('#');
        return $this->redirectToRoute('manage_articles');
    }





    // Trie du ManageArticle

    /**
     * @Route("/ManageArticlesShowThemeAsc", name="ManageArticlesShowThemeAsc")
     */
    public function ManageArticlesShowThemeAsc(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['articlesThemes' => 'ASC']);
        $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findAll();
        $themes = $this->getDoctrine()->getManager()->getRepository(Themes::class)->findAll();

        return $this->render('articles/manageArticles.html.twig', [
            'articles' => $articles,
            'pictures' => $pictures,
            'themes' => $themes,
        ]);
    }
    /**
     * @Route("/ManageArticlesShowTitleAsc", name="ManageArticlesShowTitleAsc")
     */
    public function ManageArticlesShowTitleAsc(Request $request): Response
    {
        $articles = $this->getDoctrine()->getManager()->getRepository(Articles::class)->findBy([],['title' => 'ASC']);
        $pictures = $this->getDoctrine()->getManager()->getRepository(Pictures::class)->findAll();
        $themes = $this->getDoctrine()->getManager()->getRepository(Themes::class)->findAll();

        return $this->render('articles/manageArticles.html.twig', [
            'articles' => $articles,
            'pictures' => $pictures,
            'themes' => $themes,
        ]);
    }
}