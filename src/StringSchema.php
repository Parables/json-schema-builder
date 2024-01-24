<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class StringSchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;
    use MatchSchemaType;

    public function default(string $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }

    public function minLength(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['minLength'] = $value;

        return $this;
    }

    public function maxLength(int $value): self
    {
        if ($value < 0) {
            return $this;
        }

        $this->schema['maxLength'] = $value;

        return $this;
    }

    public function pattern(string $value): self
    {
        $this->schema['pattern'] = trim($value);

        return $this;
    }

    public function contentEncoding(string $value): self
    {
        $this->schema['contentEncoding'] = trim($value);

        return $this;
    }

    public function contentMediaType(string $value): self
    {
        $this->schema['contentMediaType'] = trim($value);

        return $this;
    }

    public function contentSchema(SchemaType|string|bool $schemaType): self
    {
        $this->schema['contentSchema'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function format(): StringFormat
    {

        return new StringFormat(schema: $this->schema);
    }
}
