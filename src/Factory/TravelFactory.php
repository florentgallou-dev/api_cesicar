<?php

namespace App\Factory;

use Faker;
use App\Entity\Travel;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Travel>
 *
 * @method        Travel|Proxy create(array|callable $attributes = [])
 * @method static Travel|Proxy createOne(array $attributes = [])
 * @method static Travel|Proxy find(object|array|mixed $criteria)
 * @method static Travel|Proxy findOrCreate(array $attributes)
 * @method static Travel|Proxy first(string $sortedField = 'id')
 * @method static Travel|Proxy last(string $sortedField = 'id')
 * @method static Travel|Proxy random(array $attributes = [])
 * @method static Travel|Proxy randomOrCreate(array $attributes = [])
 * @method static TravelRepository|RepositoryProxy repository()
 * @method static Travel[]|Proxy[] all()
 * @method static Travel[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Travel[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Travel[]|Proxy[] findBy(array $attributes)
 * @method static Travel[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Travel[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TravelFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private UserRepository $users)
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
        $faker = Faker\Factory::create('fr_FR');
        $geolocalisations = [
            [49.398727, 1.066153],
            [49.413940, 1.144741],
            [49.420202, 1.090068],
            [49.479810, 1.039546],
            [49.498714, 1.146148],
            [49.544962, 1.051310],
            [49.356619, 1.006793],
            [49.339513, 1.095627],
            [49.297965, 1.015903],
            [49.260965, 0.932748],
        ];

        return [
            'name' => $faker->text(50),
            'toCesi' => $faker->boolean(),
            'position' => $faker->randomElement($geolocalisations),
            'departure_date' => $faker->dateTimeBetween('now', '+90 days'),
            'number_seats' => $faker->randomElement($array = [1, 2, 3, 4, 5, 6]),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Travel $travel): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Travel::class;
    }
}
