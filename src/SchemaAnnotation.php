<?php

namespace Parables\JsonSchemaBuilder;

trait SchemaAnnotation
{
    use Schema;
    use ThrowIfEmpty;

    public function title(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['title'] = $value;

        return $this;
    }

    public function description(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['description'] = $value;

        return $this;
    }

    public function example(mixed $value): self
    {
        $this->schema['examples'][] = $value;

        return $this;
    }

    public function comment(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$comment'] = $value;

        return $this;
    }

    public function enum(array $value): self
    {
        $this->schema['enum'] = array_unique_values($value);

        return $this;
    }

    public function const(mixed $value): self
    {
        $this->schema['const'] = $value;

        return $this;
    }

    public function readOnly(bool $value = true): self
    {
        $this->schema['readOnly'] = $value;

        return $this;
    }

    public function writeOnly(bool $value = true): self
    {
        $this->schema['writeOnly'] = $value;

        return $this;
    }

    public function deprecated(bool $value = true): self
    {
        $this->schema['deprecated'] = $value;

        return $this;
    }

    public function link(LDO $ldo): self
    {
        $this->schema['links'][] = $ldo->build();

        return $this;
    }
}
