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
    public function ReadUser(Request $request): Response
    {
        $currentUser = $this->getUser();
        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->findOneBy(['email' => $currentUser->getEmail()]);
        // var_dump($currentUser);
        // var_dump($user);

        return $this->render('users/readOneUser.html.twig', [
            'user' => $user,
            'currentUser' => $currentUser,
        ]);
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
     * @Route("/updateUser/{username}", name="update_user")
     */
    public function UpdateUsers(Request $request): Response
    {
        exit ('Je suis dans le UserController -> UpdateUser');
    }

    /**
     * @Route("/deleteUser/{username}", name="delete_user")
     */
    public function DeleteUsers(Users $user, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('all_users');
    }



}