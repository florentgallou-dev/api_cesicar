<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsController]
class MeController
{
    public function __construct(private Security $security)
    {
        
    }

    public function __invoke()
    {
        if(!$user = $this->security->getUser()){
            throw new UnauthorizedHttpException('OAuth', 'Erreur d\'authentification');
        }
        return $user;
    }
}