<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType as JsonSchemaType;

class JsonSchema implements JsonSchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use DataTypes;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;

    const LATEST_JSON_SCHEMA = 'https://json-schema.org/draft/2020-12/schema';
    const LATEST_HYPER_SCHEMA = 'https://json-schema.org/draft/2019-09/hyper-schema';

    public static function create(array|bool $value = []): self
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
        ?string $value = self::LATEST_JSON_SCHEMA,
    ): self {
        $value = match(true){
            is_url($value) => trim($value),
            default => self::LATEST_JSON_SCHEMA,
        };

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
