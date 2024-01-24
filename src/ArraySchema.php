<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class ArraySchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;
    use MatchSchemaType;

    public function default(array $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }

    public function minItems(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['minItems'] = $value;

        return $this;
    }

    public function maxItems(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['maxItems'] = $value;

        return $this;
    }

    public function uniqueItems(bool $value = true): self
    {
        $this->schema['uniqueItems'] = $value;

        return $this;
    }

    public function contains(SchemaType|string|bool $schemaType): self
    {
        $this->schema['contains'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function minContains(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['minContains'] = $value;

        return $this;
    }

    public function maxContains(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['maxContains'] = $value;

        return $this;
    }

    public function items(SchemaType|string|bool $schemaType): self
    {
        $this->schema['items'] = self::matchSchemaType(schemaType: $schemaType);


        return $this;
    }

    public function prefixItems(SchemaType|string|bool $schemaType): self
    {
        $this->schema['prefixItems'][] = self::matchSchemaType(schemaType: $schemaType);


        return $this;
    }

    public function unevaluatedItems(bool $value): self
    {
        $this->schema['unevaluatedItems'] = $value;

        return $this;
    }
}
