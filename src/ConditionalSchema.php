<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

trait ConditionalSchema
{
    use BuildSchema;
    use Schema;
    use MatchSchemaType;

    public function if(SchemaType|string|bool $schemaType): self
    {
        $this->schema['if'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function then(SchemaType|string|bool $schemaType): self
    {
        $this->schema['then'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function else(SchemaType|string|bool $schemaType): self
    {
        $this->schema['else'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function not(SchemaType|string|bool $schemaType): self
    {
        $this->schema['not'] = self::matchSchemaType(schemaType: $schemaType);

    }
}
