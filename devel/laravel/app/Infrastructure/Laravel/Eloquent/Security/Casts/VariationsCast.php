<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use App\Domain\Security\ValueObjects\Description;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\Variations\{
    NoVariations,
    Variations,
    VariationsInterface,
};

// TODO: add test coverage for variations deserialization from persisted JSON

final class VariationsCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): VariationsInterface
    {
        return $this->decode($value);
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        return match (true) {
            ($value instanceof VariationsInterface) => $this->encode($key, $value),
            default => dd($value),
        };
    }

    private function decode(?string $value): VariationsInterface
    {
        if (is_null($value)) {
            return NoVariations::create();
        }

        $decoded = json_decode($value, true);

        return is_array($decoded)
            ? Variations::fromStrings($decoded)
            : NoVariations::create();
    }

    private function encode(string $key, VariationsInterface $variations): array
    {
        return match (true) {
            ($variations instanceof NoVariations) => [$key => null],
            default => [$key => json_encode(
                array_map(
                    fn (Description $variation) => (string) $variation,
                    $variations->all())
                )],
        };
    }
}
