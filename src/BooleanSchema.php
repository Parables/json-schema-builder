<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class BooleanSchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;

    public function default(bool $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }
}
