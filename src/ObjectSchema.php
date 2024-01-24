<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class ObjectSchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;
    use ThrowIfEmpty;
    use MatchSchemaType;

    public function default(array|bool $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }

    /** sets the properties to an empty object */
    public function properties(): self
    {
        $this->schema['properties'] = [];

        return $this;
    }

    public function property(string $name, SchemaType|string|bool $schemaType): self
    {
        self::throwIfEmpty(value: $name, label: __METHOD__);
        $this->schema['properties'][$name] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function required(array $value): self
    {
        $value = array_unique_values(array_filter($value));
        $this->schema['required'] = $value;

        return $this;
    }

    public function dependentSchema(string $key, SchemaType|string|bool $schemaType): self
    {
        self::throwIfEmpty(value: $key, label: __METHOD__);
        $this->schema['dependentSchemas'][$key] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function dependentRequired(string $key, array $value): self
    {
        self::throwIfEmpty(value: $key, label: __METHOD__);
        $value = array_unique(array_filter($value));
        $this->schema['dependentRequired'][$key] = $value;

        return $this;
    }

    public function minProperties(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['minProperties'] = $value;

        return $this;
    }

    public function maxProperties(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['maxProperties'] = $value;

        return $this;
    }

    public function propertyNames(StringSchema|string|bool $stringSchema): self
    {
        $this->schema['propertyNames'] = self::matchSchemaType(schemaType: $schemaType);


        return $this;
    }

    public function patternProperties(string $key, SchemaType|string|bool $schemaType): self
    {
        $this->schema['patternProperties'][$key] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function additionalProperties(SchemaType|string|bool $schemaType): self
    {
        $this->schema['additionalProperties'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function unevaluatedProperties(bool $value): self
    {
        $this->schema['unevaluatedProperties'] = $value;

        return $this;
    }
}
