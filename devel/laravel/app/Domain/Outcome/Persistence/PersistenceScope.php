<?php

namespace App\Domain\Outcome\Persistence;

/**
 * Defines which fields of a domain model should be persisted.
 */
final class PersistenceScope
{
    /** @var string[] */
    private array $fields;

    private function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public static function all(): self
    {
        return new self(['*']);
    }

    public static function none(): self
    {
        return new self([]);
    }

    public static function of(array $fields): self
    {
        return new self($fields);
    }

    public function includes(string $field): bool
    {
        return in_array('*', $this->fields, true) || in_array($field, $this->fields, true);
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function isEmpty(): bool
    {
        return empty($this->fields);
    }
}
