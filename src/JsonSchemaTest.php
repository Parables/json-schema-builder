<?php

namespace Parables\JsonSchemaBuilder;

it('can build an empty schema', function () {
    expect(JsonSchema::create()->build())->toBe([]);
});

it('can create a basic schema', function () {
    expect(JsonSchema::create()->string()->build())->toBe(['type' => 'string']);
    expect(JsonSchema::create()->number()->build())->toBe(['type' => 'number']);
    expect(JsonSchema::create()->integer()->build())->toBe(['type' => 'integer']);
    expect(JsonSchema::create()->boolean()->build())->toBe(['type' => 'boolean']);
    expect(JsonSchema::create()->null()->build())->toBe(['type' => 'null']);
    expect(JsonSchema::create()->array()->build())->toBe(['type' => 'array']);
    expect(JsonSchema::create()->object()->build())->toBe(['type' => 'object']);
});

it('matches type of default values with the schema type', function () {
    // NOTE: PestPHP might coerce the argument passed to the default method
    // PestPHP will fail with TypeError is it can coerce the value

    // NOTE: The finally schema returned by the `build()` method is filtered with `array_filter()` to remove empty values
    expect(JsonSchema::create()->string()->default('test')->build())->toBe(['type' => 'string', 'default' => 'test']);
    expect(JsonSchema::create()->number()->default(1.5)->build())->toBe(['type' => 'number', 'default' => 1.5]);
    expect(JsonSchema::create()->integer()->default(1)->build())->toBe(['type' => 'integer', 'default' => 1]);
    expect(JsonSchema::create()->boolean()->default(true)->build())->toBe(['type' => 'boolean', 'default' => true]);
    expect(JsonSchema::create()->null()->default('null')->build())->toBe(['type' => 'null', 'default' => 'null']);
    expect(JsonSchema::create()->array()->default(['one'])->build())->toBe(['type' => 'array', 'default' => ['one']]);
    expect(JsonSchema::create()->object()->default(['key' => 'value'])->build())->toBe(['type' => 'object', 'default' => ['key' => 'value']]);
});

it('will uniquely identify the schema', function () {
    $schema = JsonSchema::create()->id('http://localhost/docs/schema/person')->build();
    $expected = ['$id' => 'http://localhost/docs/schema/person'];

    expect($schema)->toBe($expected);
});

it('will use the latest $schema draft if no value is given to the schema() method', function (mixed $schema, string $expected) {
    $schema = JsonSchema::create()->schema($schema)->build();
    $expected = ['$schema' => $expected];

    expect($schema)->toBe($expected);
})->with([
    'an empty string' => ['', 'https://json-schema.org/draft/2020-12/schema'],
    'no argument' => [null, 'https://json-schema.org/draft/2020-12/schema'],
    'a non-url string' => ['not a url', 'https://json-schema.org/draft/2020-12/schema'],
    'a url string' => ['http://json-schema.org/schema#', 'http://json-schema.org/schema#'],

]);

it('can build the schema with some meta-data', function () {
    $schema = JsonSchema::create()
        ->id('http://localhost/docs/schema/person')
        ->schema()
        ->title('Person')
        ->description('A Person represents a human being, collectively known as People')
        ->comment(
            'In schoolman, there is nothing like students or teachers or parents. '.
            'All these are people who belong to a group. A group is basically people who share the same tags'
        )
        ->example([
            'personName' => [
                'givenName' => 'Iron',
                'familyName' => 'Man',
            ],
            'genderAtBirth' => 'male',
        ])
        ->example([
            'personId' => 'person-2',
            'personName' => [
                'givenName' => 'Black',
                'familyName' => 'Widow',
            ],
            'genderAtBirth' => 'female',
            'dateOfBirth' => 'Feb 12, 1997',
            'maritalStatus' => 'widow',
        ])
        ->build();

    $expected = [
        '$id' => 'http://localhost/docs/schema/person',
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        'title' => 'Person',
        'description' => 'A Person represents a human being, collectively known as People',
        '$comment' => 'In schoolman, there is nothing like students or teachers or parents. '.
                    'All these are people who belong to a group. A group is basically people who share the same tags',
        'examples' => [
            [
                'personName' => [
                    'givenName' => 'Iron',
                    'familyName' => 'Man',
                ],
                'genderAtBirth' => 'male',
            ],
            [
                'personId' => 'person-2',
                'personName' => [
                    'givenName' => 'Black',
                    'familyName' => 'Widow',
                ],
                'genderAtBirth' => 'female',
                'dateOfBirth' => 'Feb 12, 1997',
                'maritalStatus' => 'widow',
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('can validate a string schema', function (string $method, mixed $value) {
    $schema = JsonSchema::create()->string()->$method($value)->build();
    $expected = ['type' => 'string', $method => $value];

    expect($schema)->toBe($expected);
})->with([
    ['minLength', 5],
    ['maxLength', 10],
    ['pattern', '^(\\([0-9]{3}\\))?[0-9]{3}-[0-9]{4}$'],
    ['contentEncoding', 'base64'],
    ['contentMediaType', 'application/json'],
]);

it('can build a schema for the contentSchema validation of the string schema', function () {
    $schema = JsonSchema::create()
        ->string()
        ->contentSchema(
            schemaType: SchemaType::create()
                ->object()
                ->property(name: 'name', schemaType: SchemaType::create()->string())
                ->property(name: 'age', schemaType: SchemaType::create()->integer())
                ->required(['name', 'age'])
        )
        ->build();

    $expected = [
        'type' => 'string',
        'contentSchema' => [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string'],
                'age' => ['type' => 'integer'],
            ],
            'required' => ['name', 'age'],
        ],
    ];
    expect($schema)->toBe($expected);

});

it('supports all the string validation formats', function (string $format) {
    $method = camel_case($format);
    $schema = JsonSchema::create()->string()->format()->$method()->build();
    $expected = ['type' => 'string', 'format' => $format];

    expect($schema)->toBe($expected);
})->with([
    'date',
    'time',
    'date-time',
    'duration',
    'regex',
    'email',
    'idn-email',
    'hostname',
    'idn-hostname',
    'ipv4',
    'ipv6',
    'json-pointer',
    'relative-json-pointer',
    'uri',
    'uri-reference',
    'uri-template',
    'iri',
    'iri-reference',
    'uuid',
]);

it('supports $dynamicRef and $dynamicAnchor', function () {

    $schema = JsonSchema::create()
        ->schema()
        ->id('https://example.com/tree')
        ->dynamicAnchor('node')
        ->type('object')
        ->property('data', true)
        ->property('children', SchemaType::create()->array()->items(SchemaType::create()->dynamicRef('#node')))
        ->build();

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        '$id' => 'https://example.com/tree',
        '$dynamicAnchor' => 'node',
        'type' => 'object',
        'properties' => [
            'data' => true,
            'children' => [
                'type' => 'array',
                'items' => [
                    '$dynamicRef' => '#node',
                ],
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('supports if-then-else keywords', function () {

    $schema = JsonSchema::create()
        ->object()
        ->property('street_address', SchemaType::create()->string())
        ->property('country', SchemaType::create()->string(include: false)
            ->default('United States of America')
            ->enum(['United States of America', 'Canada'])
        )->if(SchemaType::create()->object(include: false)
        ->property('country', SchemaType::create()->const('United States of America'))
        )
        ->then(SchemaType::create()->object(include: false)
            ->property('postal_code', SchemaType::create()->string(include: false)->pattern('[0-9]{5}(-[0-9]{4})?'))
        )
        ->else(SchemaType::create()->object(include: false)
            ->property('postal_code', SchemaType::create()->string(include: false)->pattern('[A-Z][0-9][A-Z] [0-9][A-Z][0-9]'))
        )
        ->build();

    $expected = [
        'type' => 'object',
        'properties' => [
            'street_address' => [
                'type' => 'string',
            ],
            'country' => [
                'default' => 'United States of America',
                'enum' => ['United States of America', 'Canada'],
            ],
        ],
        'if' => [
            'properties' => [
                'country' => ['const' => 'United States of America'],
            ],
        ],
        'then' => [
            'properties' => [
                'postal_code' => ['pattern' => '[0-9]{5}(-[0-9]{4})?'],
            ],
        ],
        'else' => [
            'properties' => [
                'postal_code' => ['pattern' => '[A-Z][0-9][A-Z] [0-9][A-Z][0-9]'],
            ],
        ],
    ];

    expect($schema)->toBe($expected);
});

test('it supports links', function () {

    $schema = JsonSchema::create()
        ->object()
        ->properties()
        ->link(LDO::create()
            ->rel('list')
            ->href('http://example.com/thing')
        )
        ->link(LDO::create()
            ->rel('self')
            ->href('http://example.com/thing/{id}')
        )
        ->link(LDO::create()
            ->rel('color')
            ->href('http://example.com/thing/{id}/color/{color}')
            ->mediaType('application/vnd-color+json')
        )
        ->build();

    $expected = [
        'type' => 'object',
        'properties' => [],
        'links' => [
            ['rel' => 'list',   'href' => 'http://example.com/thing'],
            ['rel' => 'self',   'href' => 'http://example.com/thing/{id}'],
            ['rel' => 'color',  'href' => 'http://example.com/thing/{id}/color/{color}', 'mediaType' => 'application/vnd-color+json'],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('lowercase the `rel` attribute in the link', function () {

    $schema = JsonSchema::create()
        ->link(LDO::create()
            ->rel('LIST')
            ->href('http://example.com/thing')
        )
        ->link(LDO::create()
            ->rel('Self')
            ->href('http://example.com/thing/{id}')
        )
        ->link(LDO::create()
            ->rel('coLOR')
            ->href('http://example.com/thing/{id}/color/{color}')
        )
        ->build();

    $expected = [
        'links' => [
            ['rel' => 'list', 'href' => 'http://example.com/thing'],
            ['rel' => 'self', 'href' => 'http://example.com/thing/{id}'],
            ['rel' => 'color', 'href' => 'http://example.com/thing/{id}/color/{color}'],
        ],
    ];

    expect($schema)->toBe($expected);
});

it('supports targetSchema and submissionSchema', function () {
    $schema = JsonSchema::create()
        ->link(LDO::create()
            ->rel('target')
            ->href('http://example.com/things{?color}')
            ->targetSchema(SchemaType::create()
                ->object(false)
                ->required(['one', 'two'])
            )
        )
        ->build();

    $expected = [
        'links' => [
            [
                'rel' => 'target',
                'href' => 'http://example.com/things{?color}',
                'targetSchema' => [
                    'required' => ['one', 'two'],
                ],
            ],
        ],
    ];

    expect($schema)->toBe($expected);

});
