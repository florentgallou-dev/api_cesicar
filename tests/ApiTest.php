<?php

namespace App\tests;

use App\Entity\Travel;
use App\Factory\UserFactory;
use App\Factory\TravelFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ApiTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;
    public function testGetCollection(): void
    {
        // Create 100 travels using our factory
        UserFactory::createMany(50);
        TravelFactory::createMany(100);
    
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/travels');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Travel',
            '@id' => '/api/travels',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100
        ]);
        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(100, $response->toArray()['hydra:member']);
        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Travel::class);
    }
}