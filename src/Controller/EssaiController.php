<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

include __DIR__ . '/../../assets/variable.php';

class EssaiController extends AbstractController
{
    /**
     * @Route ("/numbers", name="numbers")
     */
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * @Route ("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'titreSite' => $_SESSION['titre'],
            'text' => 'Ici un text',
        ]);
    }
}