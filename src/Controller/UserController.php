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
        $userCourant = $this->getUser()->getEmail();
        // $currentUser = $this->get('security.token_storage')->getTok:en()->getUser();
        return $this->render('users/readUser.html.twig', [
            // 'titreSite' => $_SESSION['titre'],
            'user' => $user,
            'userCourant' => $userCourant,
        ]);
    }

    /**
     * @Route("/showAllSortUserName", name="showArtSortNom")
     */
    public function ShowAllUserSortNom(Request $request): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findBy([],['name' => 'ASC']);
        $userCourant = $this->getUser()->getEmail();
        return $this->render('users/readUser.html.twig', [
            // 'titreSite' => $_SESSION['titre'],
            'user' => $user,
            'userCourant' => $userCourant,
        ]);
    }

    /**
     * @Route("/showAllSortUserPrenom", name="showArtSortPrenom")
     */
    public function ShowAllUserSortPrenom(Request $request): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findBy([],['firstname' => 'ASC']);
        $userCourant = $this->getUser()->getEmail();
        return $this->render('users/readUser.html.twig', [
            // 'titreSite' => $_SESSION['titre'],
            'user' => $user,
            'userCourant' => $userCourant,
        ]);
    }

    /**
     * @Route("/updateUser/{id}", name="update_user")
     */
    public function UpdateUsers(Request $request): Response
    {

    }

    /**
     * @Route("/deleteUser/{id}", name="delete_user")
     */
    public function DeleteUsers(Users $user, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('all_users');
    }
}