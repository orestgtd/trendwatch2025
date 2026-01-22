<?php

namespace App\Shared;

use App\Shared\Result;
use Carbon\Carbon;

class Date
{
    const STANDARD = 'Y-m-d';

    private Carbon $carbon;

    private function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }

    public static function fromCarbon(Carbon $carbon): self
    {
        return new self ($carbon);
    }

    public static function fromString(string $value): self
    {
        return new self (Carbon::parse($value));
    }

    /** @return Result<Date> */
    public static function tryFrom(string $value): Result
    {
        return Result::success(self::fromString($value));
    }

    public static function today(): self
    {
        return new self(Carbon::now());
    }

    public function isBeforeToday(): bool
    {
        return $this->carbon->lt(Carbon::today());
    }

    public function isBefore(Date $other): bool
    {
        return $this->carbon->lt($other->carbon);
    }

    public function toString(): string
    {
        return $this->carbon->format(self::STANDARD);
    }

    public function toFormattedString(): string
    {
        return self::format($this->carbon);
    }

    public function year(): string
    {
        return (string) $this->carbon->year;
    }

    public function equalTo(string $value): bool
    {
        return $this->carbon->equalTo($value);
    }

    public static function format(string|Carbon $value): string
    {
        return match(gettype($value)) {
            'string' => Carbon::parse($value)->format(self::STANDARD),
            'object' => $value->format(self::STANDARD),
        };
    }
}
