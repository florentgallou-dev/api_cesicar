<?php
namespace App\DataFixtures;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\TravelFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
            $user1->setFirstName('Florent');
            $user1->setLastName('Gallou');
            $user1->setGender('homme');
            $user1->setEmail('florent.gallou@viacesi.fr');
            $user1->setPassword('$2y$13$QcJ1Bp6IvjHgVphvcJSbl.OVRf32mTDpKhXlc4S/E1hEBdGFx1FSa');
            $user1->setRoles(array('ROLE_SUPERADMIN'));
            $user1->setPosition([49.794536, 1.023416]);
            $user1->setDriver(1);
            $user1->setCarType('Ford Fiesta');
            $user1->setCarRegistration('DG-519-GG');
            $user1->setCarNbPlaces(4);

            $manager->persist($user1);
            $manager->flush();

        // UserFactory::createMany(10);
        // TravelFactory::createMany(10);
    }
}