<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {}

    public function __invoke(array $context = []): OpenApi
    {
        //call swagger dashboard
        $openApi = $this->decorated->__invoke($context);

        //give title, version and desctiption to swagger
        $openApi = $openApi->withInfo((new Model\Info(
            'Api CESICar',
            'v0',
            'Cette api fourni l\'application web et mobile CESICar.'
        ))->withExtensionProperty('info-key', 'Info value'));

        //get and generate new paths for api
        // $pathItem = $openApi->getPaths()->getPath('/api/posts/{id}');
        // $operation = $pathItem->getGet();
        // $openApi->getPaths()->addPath('/api/grumpy_pizzas/{id}', $pathItem->withGet(
        //     $operation->withParameters(array_merge(
        //         $operation->getParameters(),
        //         [new Model\Parameter('fields', 'query', 'Fields to remove of the output')]
        //     ))
        // ));

        //
        $openApi = $openApi->withExtensionProperty('key', 'Custom x-key value');
        $openApi = $openApi->withExtensionProperty('x-value', 'Custom x-value value');
        
        // to define base path URL
        $openApi = $openApi->withServers([new Model\Server('http://127.0.0.1:8000')]);

        //manage cookie for Authorize schema
        // $securitySchemas = $openApi->getComponents()->getSecuritySchemes();
        // $securitySchemas['cookieAuth'] = new \ArrayObject([
        //     'type'  => 'apiKey',
        //     'in'    => 'cookie',
        //     'name'  => 'PHPSESSID'
        // ]);

        //manage token auth
        $securitySchemas = $openApi->getComponents()->getSecuritySchemes();
        $securitySchemas['bearerAuth'] = new \ArrayObject([
            'type'          => 'http',
            'scheme'        => 'bearer',
            'bearerFormat'  => 'JWT'
        ]);


        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type'      => 'string',
                    'example'   => 'florent.gallou@viacesi.fr'
                ],
                'password' => [
                    'type'      => 'string',
                    'example'   => 'password'
                ],
            ]
        ]);

        $pathItem = new PathItem(
            post: new Operation(
                summary: 'Login pour ApiPlatform',
                description: 'Utilisez vos paramètres de connexion pour vous connecter à la documentation API',
                operationId: 'ApiCESICarLogin',
                tags: ['User'],
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'application/json'  => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                ),
                responses: [
                    '200' => [
                        'description' => 'Utilisateur connecté',
                        'content'=> [
                            'application/json'  => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.User'
                                ]
                            ]
                        ]
                    ]
                ]
            )
        );
        
        $openApi->getPaths()->addPath('/api/login', $pathItem);

        return $openApi;
    }
}