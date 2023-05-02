<?php

namespace App\Factory;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Inscription>
 *
 * @method        Inscription|Proxy create(array|callable $attributes = [])
 * @method static Inscription|Proxy createOne(array $attributes = [])
 * @method static Inscription|Proxy find(object|array|mixed $criteria)
 * @method static Inscription|Proxy findOrCreate(array $attributes)
 * @method static Inscription|Proxy first(string $sortedField = 'id')
 * @method static Inscription|Proxy last(string $sortedField = 'id')
 * @method static Inscription|Proxy random(array $attributes = [])
 * @method static Inscription|Proxy randomOrCreate(array $attributes = [])
 * @method static InscriptionRepository|RepositoryProxy repository()
 * @method static Inscription[]|Proxy[] all()
 * @method static Inscription[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Inscription[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Inscription[]|Proxy[] findBy(array $attributes)
 * @method static Inscription[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Inscription[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class InscriptionFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
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
        return [
            'created_at' => self::faker()->dateTime(),
            'travel' => TravelFactory::new(),
            'updated_at' => self::faker()->dateTime(),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Inscription $inscription): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Inscription::class;
    }
}
