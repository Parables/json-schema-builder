<?php

namespace Parables\JsonSchemaBuilder;

trait DataTypes
{
    use Schema;
    use ThrowIfEmpty;

    public function string(bool $include = true): StringSchema
    {
        if ($include) {
            $this->schema['type'] = 'string';
        }

        return new StringSchema(schema: $this->schema);
    }

    public function number(bool $include = true): NumberSchema
    {
        if ($include) {
            $this->schema['type'] = 'number';
        }

        return new NumberSchema(schema: $this->schema);
    }

    public function integer(bool $include = true): NumberSchema
    {
        if ($include) {
            $this->schema['type'] = 'integer';
        }

        return new NumberSchema(schema: $this->schema);
    }

    public function boolean(bool $include = true): BooleanSchema
    {
        if ($include) {
            $this->schema['type'] = 'boolean';
        }

        return new BooleanSchema(schema: $this->schema);
    }

    public function null(bool $include = true): EmptySchema
    {
        if ($include) {
            $this->schema['type'] = 'null';
        }

        return new EmptySchema(schema: $this->schema);
    }

    public function object(bool $include = true): ObjectSchema
    {
        if ($include) {
            $this->schema['type'] = 'object';
        }

        return new ObjectSchema(schema: $this->schema);
    }

    public function array(bool $include = true): ArraySchema
    {
        if ($include) {
            $this->schema['type'] = 'array';
        }

        return new ArraySchema(schema: $this->schema);
    }

    public function type(string|array $types): StringSchema|NumberSchema|BooleanSchema|EmptySchema|ObjectSchema|ArraySchema|self
    {
        if (is_string($types)) {
            self::throwIfEmpty(value: $types, label: __METHOD__);
        } else {
            $types = array_unique_values($types);
        }

        $this->schema['type'] = $types;

        var_dump($types);

        return match ($types) {
            'string' => $this->string(),
            'number' => $this->number(),
            'integer' => $this->integer(),
            'boolean' => $this->boolean(),
            'null' => $this->null(),
            'object' => $this->object(),
            'array' => $this->array(),
            default => $this,
        };

    }
}
