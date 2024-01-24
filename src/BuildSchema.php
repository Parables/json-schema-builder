<?php

namespace Parables\JsonSchemaBuilder;

trait BuildSchema
{
    use Schema;

    public function build(): array
    {
        return $this->schema;
    }
}
