<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

include __DIR__ . '/../../assets/variable.php';

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'titreSite' => $_SESSION['titre'],
            'text' => 'Ici un text',
        ]);
    }
}
