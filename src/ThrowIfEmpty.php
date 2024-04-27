<?php

namespace Parables\JsonSchemaBuilder;

use LogicException;

trait ThrowIfEmpty
{
    private static function throwIfEmpty(
        string $value,
        string $label,
        bool $shouldThrow = true,
        string $errorMessage = null,
    ): void {
        $value = trim($value);

        if ($shouldThrow && empty($value)) {
            throw new LogicException(
                $errorMessage ?? "The value of $label must not be an empty string"
            );
        }
    }
}
