<?php

namespace App\Controller;

use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(): void
    {
        throw new Exception('Erreur d\'authentification utilisez le JWT Handler');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(): void
    {
        dd('michel');
        throw new Exception('Erreur d\'authentification utilisez le JWT Handler');
    }

}
