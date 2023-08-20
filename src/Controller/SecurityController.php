<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json([
            "username"  => $user->getUserIdentifier(),
            "roles"     => $user->getRoles()
        ]);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        return $this->json([], 204);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $EMAIL_REGEX = '/^\w+([\.-]?\w+)*(@viacesi.fr)+$/';
        $PWD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%]).{8,24}$/';

        $datas = json_decode($request->getContent(), true);

        //check if user already exist
        if($userRepository->findOneBy(['email' => $datas['email']])) {
            return new JsonResponse([
                'error' => 'Cet email est déjà utilisé'
            ], 409);
        }
        elseif(!preg_match($EMAIL_REGEX, $datas['email'])) {
            return new JsonResponse([
                'error' => 'Votre email n\'est pas valide'
            ], 410);
        }
        elseif(!preg_match($PWD_REGEX, $datas['password'])) {
            return new JsonResponse([
                'error' => 'Votre mot de passe n\'est pas assez sécurité'
            ], 411);
        }else {

            $user = new User();
            $user->setEmail($datas['email']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $datas['password']
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse([
                'success' => 'Votre compte a bien été créé, veuillez vous connecter'
            ], 200);
        }
    }

}
