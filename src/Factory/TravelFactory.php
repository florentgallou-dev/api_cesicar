<?php

namespace App\Factory;

use Faker;
use App\Entity\Travel;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\ModelFactory;
use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    private $client;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private UserRepository $users, HttpClientInterface $client)
    {
        $this->client = $client;
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

        $stringToQuery = [
            "77 Rue du Général Leclerc, 76000 Rouen",
            "1 Imp. Massard, 76100 Rouen",
            "10 Rue Saint-Laurent, 27700 Heuqueville",
            "516 Rue Jean Jaurès, 76770 Houppeville",
            "5-3 Rue de Lanarck, 76190 Yvetot",
            "97 Pl. Micheline Ostermeyer, 76970 Grémonville",
            "161 Rue de la Pierre aux Pages, 76410 Cleon",
            "1 All. du Chat Rouge, 76410 Tourville-la-Rivière",
            "18 Rue des Vingt Acres, 27370 La Saussaye",
            "7 Chem. des Bargues, 27370 Le Thuit-de-l'Oison",
            "75 Rte de Quillebeuf, 27680 Trouville-la-Haule",
            "102 Rue des frères Jussieu, 76230 Isneauville",
            "4 Rte de Montville, 76770 Malaunay",
            "9 Sent. des Jumelles, 76710 Montville",
            "30 Rte de Louviers, 27930 Le Boulay-Morin",
            "2 rue de la roche, 27100 Val-de-Reuil"
        ];

        $query = $this->getAddressData($faker->randomElement($stringToQuery));
        $orderCoordonates = [$query['features'][0]['geometry']['coordinates'][1], $query['features'][0]['geometry']['coordinates'][0]];

        $address = [
            "label"         => $query['features'][0]['properties']['label'],
            "housenumber"   => $query['features'][0]['properties']['housenumber'],
            "id"            => $query['features'][0]['properties']['id'],
            "name"          => $query['features'][0]['properties']['name'],
            "postcode"      => $query['features'][0]['properties']['postcode'],
            "citycode"      => $query['features'][0]['properties']['citycode'],
            "position"      => $orderCoordonates,
            "city"          => $query['features'][0]['properties']['city'],
            "context"       => $query['features'][0]['properties']['context'],
            "street"        => $query['features'][0]['properties']['street']
        ];

        $drivers = $this->users->findDrivers();

        return [
            'name' => $faker->text(50),
            'toCesi' => $faker->boolean(),
            // 'position' => $faker->randomElement($geolocalisations),
            'address' => $address,
            'departure_date' => $faker->dateTimeBetween('now', '+90 days'),
            'number_seats' => $faker->randomElement($array = [1, 2, 3, 4, 5, 6]),
            'user' => $faker->randomElement($drivers),
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

    public function getAddressData(string $address): array
    {
        $response = $this->client->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search/?q='.$address
        );

        return $response->toArray();
    }
}
