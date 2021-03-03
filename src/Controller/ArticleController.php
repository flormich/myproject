<?php

namespace App\Controller;

use App\Entity\Users;

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
        // $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findAll();
        // $userCourant = $this->getUser()->getEmail();
        // return $this->render('users/readUser.html.twig', [
        //     // 'titreSite' => $_SESSION['titre'],
        //     'user' => $user,
        //     'userCourant' => $userCourant,
        // ]);
    }

    /**
     * @Route("/readArticle", name="show_all_article")
     */
    public function ShowAllArticle(Request $request): Response
    {

    }

    /**
     * @Route("/showArticle/{id}", name="show_one_article")
     */
    public function ShowOneArticle(Request $request): Response
    {
        
    }

    /**
     * @ROute("/updateArticle", name="update_article")
     */
    public function UpdateArticle(Request $request): Response
    {

    }

    /**
     * @Route("deleteArticle", name="delete_article")
     */
    public function DeleteArticle(Request $request): Response
    {

    }
}