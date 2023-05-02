<?php

namespace App\Factory;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Conversation>
 *
 * @method        Conversation|Proxy create(array|callable $attributes = [])
 * @method static Conversation|Proxy createOne(array $attributes = [])
 * @method static Conversation|Proxy find(object|array|mixed $criteria)
 * @method static Conversation|Proxy findOrCreate(array $attributes)
 * @method static Conversation|Proxy first(string $sortedField = 'id')
 * @method static Conversation|Proxy last(string $sortedField = 'id')
 * @method static Conversation|Proxy random(array $attributes = [])
 * @method static Conversation|Proxy randomOrCreate(array $attributes = [])
 * @method static ConversationRepository|RepositoryProxy repository()
 * @method static Conversation[]|Proxy[] all()
 * @method static Conversation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Conversation[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Conversation[]|Proxy[] findBy(array $attributes)
 * @method static Conversation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Conversation[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ConversationFactory extends ModelFactory
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
            'subject' => self::faker()->text(150),
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
            // ->afterInstantiate(function(Conversation $conversation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Conversation::class;
    }
}
