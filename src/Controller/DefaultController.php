<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_racine', methods: 'GET')]
    public function index(Security $security): Response
    {
        // Vérifier si l'utilisateur est connecté
        if ($this->getUser()) {
            // Rediriger vers la route "/home" si connecté
            return $this->redirectToRoute('app_tweet_index');
        } else {
            // Rediriger vers la route "/login" si non connecté
            return $this->redirectToRoute('app_login');
        }
    }
}
