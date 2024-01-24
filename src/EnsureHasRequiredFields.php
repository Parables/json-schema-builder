<?php

namespace Parables\JsonSchemaBuilder;

use LogicException;

trait EnsureHasRequiredFields
{
    /**
     * @return array<int,string>
     */
    abstract private static function requiredFields(): array;

    // abstract private static function errorMessage(): string;

    private static function errorMessage(): string
    {
        return 'The following fields are required but were not provided: '
                .implode(', ', self::requiredFields());
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function ensureRequiredFieldsAreNotEmpty(array $payload, string $payloadKey): void
    {
        $violations = array_filter(
            array_map(
                fn ($requiredField) => (! array_key_exists($requiredField, $payload) || empty($payload[$requiredField]))
                    ? "$requiredField is required but was not provided"
                : null,
                self::requiredFields()
            )
        );

        if (! empty($violations)) {
            throw new LogicException(implode('/n', $violations));
        }
    }
}
