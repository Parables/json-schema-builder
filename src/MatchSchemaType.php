<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

trait MatchSchemaType
{
    private static function matchSchemaType(SchemaType|string|bool $schemaType): bool|array {
return  match (true) {
            is_bool($schemaType) => $schemaType,
            is_string($schemaType) => ['$ref' => $schemaType],
            default => $schemaType->build(),
        };
}
}
