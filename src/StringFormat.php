<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

/**
 * @see https://json-schema.org/understanding-json-schema/reference/string#format
 * TODO: document all the methods here
 */
class StringFormat implements SchemaType
{
    use BuildSchema;
    use Schema;

    public function date(): self
    {
        $this->schema['format'] = 'date';

        return $this;
    }

    public function time(): self
    {
        $this->schema['format'] = 'time';

        return $this;
    }

    public function dateTime(): self
    {
        $this->schema['format'] = 'date-time';

        return $this;
    }

    public function duration(): self
    {
        $this->schema['format'] = 'duration';

        return $this;
    }

    public function regex(): self
    {
        $this->schema['format'] = 'regex';

        return $this;
    }

    public function email(): self
    {
        $this->schema['format'] = 'email';

        return $this;
    }

    public function idnEmail(): self
    {
        $this->schema['format'] = 'idn-email';

        return $this;
    }

    public function hostname(): self
    {
        $this->schema['format'] = 'hostname';

        return $this;
    }

    public function idnHostname(): self
    {
        $this->schema['format'] = 'idn-hostname';

        return $this;
    }

    public function ipv4(): self
    {
        $this->schema['format'] = 'ipv4';

        return $this;
    }

    public function ipv6(): self
    {
        $this->schema['format'] = 'ipv6';

        return $this;
    }

    public function jsonPointer(): self
    {
        $this->schema['format'] = 'json-pointer';

        return $this;
    }

    public function relativeJsonPointer(): self
    {
        $this->schema['format'] = 'relative-json-pointer';

        return $this;
    }

    public function uri(): self
    {
        $this->schema['format'] = 'uri';

        return $this;
    }

    public function uriReference(): self
    {
        $this->schema['format'] = 'uri-reference';

        return $this;
    }

    public function uriTemplate(): self
    {
        $this->schema['format'] = 'uri-template';

        return $this;
    }

    public function iri(): self
    {
        $this->schema['format'] = 'iri';

        return $this;
    }

    public function iriReference(): self
    {
        $this->schema['format'] = 'iri-reference';

        return $this;
    }

    public function uuid(): self
    {
        $this->schema['format'] = 'uuid';

        return $this;
    }
}
