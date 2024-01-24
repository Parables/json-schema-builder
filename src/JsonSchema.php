<?php

namespace Parables\JsonSchemaBuilder;


class JsonSchema
{
    use BuildSchema;
    use ConditionalSchema;
    use DataTypes;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;

    const LATEST_SCHEMA = 'https://json-schema.org/draft/2020-12/schema';

    public static function create(array|bool $value = []): static
    {
        return new self(schema: $value);
    }

    public function id(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$id'] = $value;

        return $this;
    }

    public function schema(
        ?string $value = self::LATEST_SCHEMA,
    ): self {
        $value = is_string($value) ? trim($value) : $value;
        $value = empty($value) || ! is_url($value) ? self::LATEST_SCHEMA : $value;

        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['$schema'] = $value;

        return $this;
    }

    public function base(string $value): self
    {
        self::throwIfEmpty(value: $value, label: __METHOD__);
        $this->schema['base'] = $value;

        return $this;
    }

    public function vocabulary(string $url, bool $required = true): self
    {
        if (is_url(url: $url)) {
            $this->schema['$vocabulary'][$url] = $required;
        }

        return $this;
    }
}
