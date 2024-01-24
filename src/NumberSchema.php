<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class NumberSchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;

    public function default(int|float $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }

    public function minimum(int|float $value): self
    {
        $this->schema['minimum'] = $value;

        return $this;
    }

    public function exclusiveMinimum(int|float $value): self
    {
        $this->schema['exclusiveMinimum'] = $value;

        return $this;
    }

    public function maximum(int|float $value): self
    {
        $this->schema['maximum'] = $value;

        return $this;
    }

    public function exclusiveMaximum(int|float $value): self
    {
        $this->schema['exclusiveMaximum'] = $value;

        return $this;
    }

    public function multipleOf(int $value): self
    {
        if ($value < 1) {
            return $this;
        }

        $this->schema['multipleOf'] = $value;

        return $this;
    }
}
