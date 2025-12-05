<?php

namespace App\Shared;

/**
 * @template-covariant T
 */
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
     *
     * @template U
     * @param callable(T):Result<U> $fn
     * @return self<T>|Result<U>
     * 
     */
    public function bind(callable $fn): Result
    {
        return $this->isSuccess()
            ? $fn($this->value)
            : $this;
    }

    /**
     * Map: transform the value inside Result if success.
     *
     * @template U
     * @param callable(T):U $fn
     * @return self<T>|Result<U>
     */
    public function map(callable $fn): Result {
        return $this->isSuccess()
            ? Result::success($fn($this->value))
            : $this;
    }

    /**
     * Match: fold the Result into one of two values.
     * 
     * @template R
     * @param callable(T):R $onSuccess
     * @param callable(?string):R $onFailure
     * @return R
     */
    public function match(callable $onSuccess, callable $onFailure): mixed
    {
        return $this->isSuccess()
            ? $onSuccess($this->value)
            : $onFailure($this->error);
    }

    /**
     * Tap: perform a side-effect on the value if Result is success.
     *
     * @param callable(T):void $fn
     * @return self<T>
     */
    public function tap(callable $fn): self
    {
        if ($this->isSuccess()) {
            $fn($this->value);
        }

        return $this;
    }
}