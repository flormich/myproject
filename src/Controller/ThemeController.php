<?php

namespace App\Controller;

use App\Entity\Themes;

use App\Form\AddThemeFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// include __DIR__ . '/../../assets/variable.php';

class ThemeController extends AbstractController
{
    /**
     * @Route("/addTheme", name="add_theme")
     */
    public function AddThemes(Request $request): Response
    {
        $theme = new Themes();
        $form = $this->createForm(AddThemeFormType::class, $theme);
        $form->handleRequest($request);

        $showTheme =  $this->getDoctrine()->getManager()->getRepository(Themes::class)->findBy([], ['name' => 'ASC']);
        
        if ($form->isSubmitted() && $form->isValid()){            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);
            $entityManager->flush();
            // echo $theme;

            return $this->redirectToRoute('add_theme');
        }

        return $this->render('theme/addTheme.html.twig', [
            'addThemeForm' => $form->createView(),
            'showThemes' => $showTheme,
            // 'titreSite' => $_SESSION['titre'],
        ]);
    }

    // /**
    //  * @Route("/showAllArticle", name="show_all_article")
    //  */
    // public function ShowAllArticle(Request $request): Response
    // {
        
    // }

    // /**
    //  * @Route("/showArticle/{id}", name="show_one_article")
    //  */
    // public function ShowOneArticle(Request $request): Response
    // {
        
    // }

    // /**
    //  * @ROute("/updateArticle", name="update_article")
    //  */
    // public function UpdateArticle(Request $request): Response
    // {

    // }

    // /**
    //  * @Route("deleteArticle", name="delete_article")
    //  */
    // public function DeleteArticle(Request $request): Response
    // {

    // }
}