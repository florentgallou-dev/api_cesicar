<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Proxy;
use App\Repository\UserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\RepositoryProxy;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

/**
 * @extends ModelFactory<User>
 *
 * @method        User|Proxy create(array|callable $attributes = [])
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
{    
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $localizations = [
            [
                'position'  => [49.398727, 1.066153],
                'adress'    => '',    
                'zip_code'  => 9,    
                'city'      => '',    
            ],
            [
                'position'  => [49.398727, 1.066153],
                'adress'    => '',    
                'zip_code'  => 9,    
                'city'      => '',    
            ],
        ];

        $faker = Faker\Factory::create('fr_FR');
        $driver = $faker->boolean();
        if($driver){
            $car_type = $faker->text(15);
            $car_registration = $faker->word;
            $car_nb_places = $faker->randomElement($array = [1, 2, 3, 4, 5, 6]);
        }

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->email,
            'position' => [$faker->latitude(49.324733, 49.496122), $faker->longitude(0.982762, 1.295804)],
            'password' => 'password',
            'gender' => $faker->randomElement($array = ['homme', 'femme', 'autre']),
            'isVerified' => $faker->boolean(),
            'driver' => $driver,
            'car_type' => $car_type ?? '',
            'car_registration' => $car_registration ?? '',
            'car_nb_places' => $car_nb_places ?? 0,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                $user->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                ));
            })
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
