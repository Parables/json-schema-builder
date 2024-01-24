<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

trait SchemaComposers
{
    use Schema;
    use ThrowIfEmpty;
    use MatchSchemaType;

    public function ref(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$ref'] = $value;

        return $this;
    }

    public function dynamicRef(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$dynamicRef'] = $value;

        return $this;
    }

    public function dynamicAnchor(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$dynamicAnchor'] = $value;

        return $this;
    }

    public function def(string $property, SchemaType|string|bool $schemaType): self
    {
        self::throwIfEmpty(value: $property, label: __METHOD__);
        $this->schema['$defs'][$property] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function allOf(SchemaType|string|bool $schemaType): self
    {

        $this->schema['allOf'][] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function anyOf(SchemaType|string|bool $schemaType): self
    {

        $this->schema['anyOf'][] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function oneOf(SchemaType|string|bool $schemaType): self
    {

        $this->schema['oneOf'][] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }
}
