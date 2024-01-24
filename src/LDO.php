<?php

namespace Parables\JsonSchemaBuilder;

use ArrayAccess;
use LogicException;
use Parables\JsonSchemaBuilder\Contracts\SchemaType;

/**
 * A Builder class for LinkDescriptionObject
 *
 * @see https://json-schema.org/draft/2020-12/links
 * @see https://json-schema.org/specification-links#2020-12
 */
class LDO implements ArrayAccess
{
    use EnsureHasRequiredFields;
    use SchemaAnnotation;
    use MatchSchemaType;

    private static function requiredFields(): array
    {
        return ['rel', 'href'];
    }

    public static function create(): self
    {
        return new self();
    }

    public function anchor(string $value): self
    {
        $this->schema['anchor'] = trim($value);

        return $this;
    }

    public function anchorPointer(string $value): self
    {
        $this->schema['anchorPointer'] = trim($value);

        return $this;
    }

    public function rel(string|array $value): self
    {
        if (is_array($value)) {
            $value = array_unique_values(array_map('trim', strtolower($value)));
        } else {
            $value = trim($value);
        }

        $this->schema['rel'] = strtolower($value);

        return $this;
    }

    public function href(string $value): self
    {
        $this->schema['href'] = trim($value);

        return $this;
    }

    public function hrefSchema(SchemaType|string|bool $schemaType): self
    {
        $this->schema['hrefSchema'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function templatePointers(array $value): self
    {
        $this->schema['templatePointers'] = $value;

        return $this;
    }

    public function templateRequired(array $value): self
    {
        $this->schema['templateRequired'] = array_unique_values(array_map('trim', $value));

        return $this;
    }

    public function targetSchema(SchemaType|string|bool $schemaType): self
    {
        $this->schema['targetSchema'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function targetMediaType(string $value): self
    {
        $this->schema['targetMediaType'] = trim($value);

        return $this;
    }

    public function targetHints(array $value): self
    {
        $this->schema['targetHints'] = $value;

        return $this;
    }

    public function headerSchema(SchemaType|string|bool $schemaType): self
    {
        $this->schema['headerSchema'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    public function submissionMediaType(string $value = 'application/json'): self
    {
        $this->schema['submissionMediaType'] = trim($value);

        return $this;
    }

    public function submissionSchema(SchemaType|string|bool $schemaType): self
    {
        $this->schema['submissionSchema'] = self::matchSchemaType(schemaType: $schemaType);

        return $this;
    }

    /** @deprecated use  targetMediaType() or submissionMediaType() */
    public function mediaType(string $value): self
    {
        $this->schema['mediaType'] = trim($value);

        return $this;
    }

    public function build(): array
    {
        self::ensureRequiredFieldsAreNotEmpty(payload: $this->schema, payloadKey: 'LDO');

        return array_filter($this->schema);
    }

    public function offsetExists($offset): bool
    {
        return in_array(needle: $offset, haystack: $this->schema);
    }

    public function offsetGet($offset): mixed
    {
        return $this->schema[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        throw new LogicException(message: 'The LDO object is immutable... call the appropriate setter method to set the value');
    }

    public function offsetUnset($offset): void
    {
        throw new LogicException(message: 'The LDO object is immutable... simply create a new instance');
    }
}
