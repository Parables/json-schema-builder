<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType as SchemaTypeContract;

class SchemaType implements SchemaTypeContract
{
    use BuildSchema;
    use ConditionalSchema;
    use DataTypes;
    use SchemaAnnotation;
    use SchemaComposers;

    public static function create(): static
    {
        return new self(schema: []);
    }
}
