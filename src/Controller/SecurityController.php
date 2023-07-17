<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(IriConverterInterface $iriConverter, #[CurrentUser] User $user)
    {
        return new Response(null, 204, [
            'Location' => $iriConverter->getIriFromResource($user),
        ]);
        throw new Exception('Erreur d\'authentification.');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        dd('michel');
        throw new Exception('Erreur d\'enregistrement');
    }

}
