<?php

namespace Parables\JsonSchemaBuilder\Contracts;

interface SchemaType
{
    public function build(): array;
}
