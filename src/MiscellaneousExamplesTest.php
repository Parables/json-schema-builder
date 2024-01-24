<?php

namespace Parables\JsonSchemaBuilder;

it('can build a typical minimum schema', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/person.schema.json')
        ->schema('https://json-schema.org/draft/2020-12/schema')
        ->title('Person')
        ->object()
        ->property('firstName', SchemaType::create()->string()->description("The person's first name."))
        ->property('lastName', SchemaType::create()->string()->description("The person's last name."))
        ->property('age', SchemaType::create()->description('Age in years which must be equal to or greater than zero.')->integer()->minimum(0))
        ->build();

    $expected = [
        '$id' => 'https://example.com/person.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Person',
        'type' => 'object',
        'properties' => [
            'firstName' => [
                'type' => 'string',
                'description' => "The person's first name.",
            ],
            'lastName' => [
                'type' => 'string',
                'description' => "The person's last name.",
            ],
            'age' => [
                'description' => 'Age in years which must be equal to or greater than zero.',
                'type' => 'integer',
                'minimum' => 0,
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('can build a schema with ', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/arrays.schema.json')
        ->schema()
        ->description('A representation of a person, company, organization, or place')
        ->object()
        ->property('fruits', SchemaType::create()->array()->items(SchemaType::create()->string()))
        ->property('vegetables', SchemaType::create()->array()->items(SchemaType::create()->ref('#/$defs/veggie')))
        ->def('veggie', SchemaType::create()->object()
            ->required(['veggieName', 'veggieLike'])
            ->property('veggieName', SchemaType::create()->string()->description('The name of the vegetable.'))
            ->property('veggieLike', SchemaType::create()->boolean()->description('Do I like this vegetable?'))
        )
        ->build();

    $expected = [
        '$id' => 'https://example.com/arrays.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'description' => 'A representation of a person, company, organization, or place',
        'type' => 'object',
        'properties' => [
            'fruits' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'vegetables' => [
                'type' => 'array',
                'items' => ['$ref' => '#/$defs/veggie'],
            ],
        ],
        '$defs' => [
            'veggie' => [
                'type' => 'object',
                'required' => ['veggieName', 'veggieLike'],
                'properties' => [
                    'veggieName' => [
                        'type' => 'string',
                        'description' => 'The name of the vegetable.',
                    ],
                    'veggieLike' => [
                        'type' => 'boolean',
                        'description' => 'Do I like this vegetable?',
                    ],
                ],
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('can build a schema with enumerated values', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/enumerated-values.schema.json')
        ->schema()
        ->title('Enumerated Values')
        ->object()
        ->property('data', SchemaType::create()->enum([42, true, 'hello', null, [1, 2, 3]]))
        ->build();

    $expected = [
        '$id' => 'https://example.com/enumerated-values.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Enumerated Values',
        'type' => 'object',
        'properties' => [
            'data' => [
                'enum' => [42, true, 'hello', null, [1, 2, 3]],
            ],
        ],
    ];
    expect($schema)->toBe($expected);
});

it('can builld a schema with regular expression pattern', function () {
    $schema = JsonSchema::create()
        ->id('https://example.com/regex-pattern.schema.json')
        ->schema()
        ->title('Regular Expression Pattern')
        ->object()
        ->property('code', SchemaType::create()->string()->pattern('^[A-Z]{3}-\\d{3}$'))
        ->build();

    $expected = [
        '$id' => 'https://example.com/regex-pattern.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Regular Expression Pattern',
        'type' => 'object',
        'properties' => [
            'code' => [
                'type' => 'string',
                'pattern' => '^[A-Z]{3}-\\d{3}$',
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('can build a schema for a complex object', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/complex-object.schema.json')
        ->schema()
        ->title('Complex Object')
        ->object()
        ->property('name', SchemaType::create()->string())
        ->property('age', SchemaType::create()->integer()->minimum(0))
        ->property('address', SchemaType::create()->object()
            ->property('street', SchemaType::create()->string())
            ->property('city', SchemaType::create()->string())
            ->property('state', SchemaType::create()->string())
            ->property('postalCode', SchemaType::create()->string()->pattern('\\d{5}'))
            ->required(['street', 'city', 'state', 'postalCode'])
        )
        ->property('hobbies', SchemaType::create()->array()
            ->items(SchemaType::create()->string())
        )->required(['name', 'age'])
        ->build();

    $expected = [
        '$id' => 'https://example.com/complex-object.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Complex Object',
        'type' => 'object',
        'properties' => [
            'name' => [
                'type' => 'string',
            ],
            'age' => [
                'type' => 'integer',
                'minimum' => 0,
            ],
            'address' => [
                'type' => 'object',
                'properties' => [
                    'street' => [
                        'type' => 'string',
                    ],
                    'city' => [
                        'type' => 'string',
                    ],
                    'state' => [
                        'type' => 'string',
                    ],
                    'postalCode' => [
                        'type' => 'string',
                        'pattern' => '\\d{5}',
                    ],
                ],
                'required' => ['street', 'city', 'state', 'postalCode'],
            ],
            'hobbies' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
        ],
        'required' => ['name', 'age'],
    ];

    expect($schema)->toBe($expected);
});

it('can build a schema with conditional validation with dependentRequired', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/conditional-validation-dependentRequired.schema.json')
        ->schema()
        ->title('Conditional Validation with dependentRequired')
        ->object()
        ->property('foo', SchemaType::create()->boolean())
        ->property('bar', SchemaType::create()->string())
        ->dependentRequired('foo', ['bar'])
        ->build();

    $expected = [
        '$id' => 'https://example.com/conditional-validation-dependentRequired.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Conditional Validation with dependentRequired',
        'type' => 'object',
        'properties' => [
            'foo' => [
                'type' => 'boolean',
            ],
            'bar' => [
                'type' => 'string',
            ],
        ],
        'dependentRequired' => [
            'foo' => ['bar'],
        ],
    ];
    expect($schema)->toBe($expected);
});

it('can build a schema with conditional validation with dependentSchemas', function () {

    $schema = JsonSchema::create()
        ->id('https://example.com/conditional-validation-dependentSchemas.schema.json')
        ->schema()
        ->title('Conditional Validation with dependentSchemas')
        ->object()
        ->property('foo', SchemaType::create()->boolean())
        ->property('propertiesCount', SchemaType::create()->integer()->minimum(0))
        ->dependentSchema('foo', SchemaType::create()->object(include: false)
            ->required(['propertiesCount'])
            ->property('propertiesCount', SchemaType::create()->integer(include: false)->minimum(7))
        )
        ->build();

    $expected = [
        '$id' => 'https://example.com/conditional-validation-dependentSchemas.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Conditional Validation with dependentSchemas',
        'type' => 'object',
        'properties' => [
            'foo' => [
                'type' => 'boolean',
            ],
            'propertiesCount' => [
                'type' => 'integer',
                'minimum' => 0,
            ],
        ],
        'dependentSchemas' => [
            'foo' => [
                'required' => ['propertiesCount'],
                'properties' => [
                    'propertiesCount' => [
                        'minimum' => 7,
                    ],
                ],
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('can build a schema with conditional validation with if-else', function () {
    $schema = JsonSchema::create()
        ->id('https://example.com/conditional-validation-if-else.schema.json')
        ->schema()
        ->title('Conditional Validation with If-Else')
        ->object()
        ->property('isMember', SchemaType::create()->boolean())
        ->property('membershipNumber', SchemaType::create()->string())
        ->required(['isMember'])
        ->if(SchemaType::create()->object(include: false)
            ->property('isMember', SchemaType::create()->const(true))
        )
        ->then(SchemaType::create()->object(include: false)
            ->property('membershipNumber', SchemaType::create()->string()->minLength(10)->maxLength(10))
        )
        ->else(SchemaType::create()->object(include: false)
            ->property('membershipNumber', SchemaType::create()->string()->minLength(15))
        )
        ->build();

    $expected = [
        '$id' => 'https://example.com/conditional-validation-if-else.schema.json',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Conditional Validation with If-Else',
        'type' => 'object',
        'properties' => [
            'isMember' => [
                'type' => 'boolean',
            ],
            'membershipNumber' => [
                'type' => 'string',
            ],
        ],
        'required' => ['isMember'],
        'if' => [
            'properties' => [
                'isMember' => [
                    'const' => true,
                ],
            ],
        ],
        'then' => [
            'properties' => [
                'membershipNumber' => [
                    'type' => 'string',
                    'minLength' => 10,
                    'maxLength' => 10,
                ],
            ],
        ],
        'else' => [
            'properties' => [
                'membershipNumber' => [
                    'type' => 'string',
                    'minLength' => 15,
                ],
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});
