<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(): Response
    {
        return $this->render('pages/accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
