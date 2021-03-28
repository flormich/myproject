<?php

namespace App\Controller;

use App\Entity\KeyWord;

use App\Form\AddKeyWordsFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// include __DIR__ . '/../../assets/variable.php';

class KeyWordController extends AbstractController
{
    /**
     * @Route("/addKey", name="add_key")
     */
    public function AddTheme(Request $request): Response
    {
        $keyWord = new KeyWord();
        $form = $this->createForm(AddKeyWordsFormType::class, $keyWord);
        $form->handleRequest($request);

        $showKeys =  $this->getDoctrine()->getManager()->getRepository(KeyWord::class)->findBy([], ['name' => 'ASC']);
        
        if ($form->isSubmitted() && $form->isValid()){            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($keyWord);
            $entityManager->flush();

            return $this->redirectToRoute('add_key');
        }

        return $this->render('keyWords/keyWords.html.twig', [
            'addkeyForm' => $form->createView(),
            'showKeys' => $showKeys,
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