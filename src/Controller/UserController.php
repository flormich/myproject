<?php

namespace App\Controller;

use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// include __DIR__ . '/../../assets/variable.php';

class UserController extends AbstractController
{
    /**
     * @Route("/viewuser", name="view_user")
     */
    public function ReadUser(): Response
    {

    }

    /**
     * @Route("/allusers", name="all_users")
     */
    public function ReadAllUsers(Request $request): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findAll();
        return $this->render('users/readUser.html.twig', [
            // 'titreSite' => $_SESSION['titre'],
            'user' => $user,
        ]);
    }
}