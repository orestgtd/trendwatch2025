<?php

namespace App\Shared;

class Result
{
    private function __construct(
        private bool $success,
        private mixed $value = null,
        private ?string $error = null
    ) {}


    public static function success(mixed $value = null): self
    {
        return new self(true, $value);
    }

    public static function failure(string $error): self
    {
        return new self(false, null, $error);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFailure(): bool
    {
        return !$this->success;
    }

    public function getValue(): mixed
    {
        if (!$this->success) {
            throw new \LogicException('Cannot get value of a failed Result');
        }
        return $this->value;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Bind: chain another Result-producing function.
     * Like flatMap/then.
     */
    public function bind(callable $fn): self
    {
        if ($this->isFailure()) {
            return $this;
        }
        $next = $fn($this->value);
        if (! $next instanceof self) {
            throw new \LogicException("bind() callable must return a Result");
        }
        return $next;
    }

    public function map(callable $fn): Result {
        return $this->isSuccess()
            ? Result::success($fn($this->value))
            : $this;
    }

    /**
     * Match: fold the Result into one of two values.
     */
    public function match(callable $onSuccess, callable $onFailure): mixed
    {
        return $this->isSuccess()
            ? $onSuccess($this->value)
            : $onFailure($this->error);
    }

    public function tap(callable $fn): self
    {
        if ($this->isSuccess()) {
            Result::success($fn($this->value));
        }

        return $this;
    }
}