<?php

namespace Parables\JsonSchemaBuilder;

trait Schema
{
    public function __construct(
        private array $schema = []
    ) {
    }
}
