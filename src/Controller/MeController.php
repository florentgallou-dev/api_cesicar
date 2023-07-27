<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
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

    #[Route('/api/me', name: 'patch_user', methods: ['PATCH'])]
    public function patchUser(#[CurrentUser] User $user, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $REGISTRATION_REGEX = '/^[A-Z]{2}[-][0-9]{3}[-][A-Z]/';
        $datas = json_decode($request->getContent(), true);

        if(!preg_match($REGISTRATION_REGEX, $datas['car_registration'])) {
            return new JsonResponse([
                'error' => 'Votre immatriculation n\'est pas valide'
            ], 410);
        }

        $user->setFirstName($datas['first_name']);
        $user->setLastName($datas['last_name']);
        $user->setGender($datas['gender']);
        $user->setAddress(json_decode($datas['position'], true));
        $user->setDriver($datas['driver']);
        $user->setCarType($datas['car_type']);
        $user->setCarRegistration($datas['car_registration']);
        $user->setCarNbPlaces($datas['car_nb_places']);
        
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'success' => 'Vos informations on bien été enreristrées'
        ], 200);
    }
}