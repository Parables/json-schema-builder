<?php

namespace Parables\JsonSchemaBuilder;

use Parables\JsonSchemaBuilder\Contracts\SchemaType;

class EmptySchema implements SchemaType
{
    use BuildSchema;
    use ConditionalSchema;
    use Schema;
    use SchemaAnnotation;
    use SchemaComposers;

    public function default(mixed $value): self
    {
        $this->schema['default'] = $value;

        return $this;
    }
}
